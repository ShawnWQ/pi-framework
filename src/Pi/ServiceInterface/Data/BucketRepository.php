<?hh

namespace Pi\ServiceInterface\Data;

use Pi\Odm\MongoRepository;
use SpotEvents\ServiceModel\Types\EventAttendant;
use Pi\Odm\BucketCollection;
use Pi\Redis\Interfaces\IRedisClientsManager;
use Pi\Host\HostProvider;

class BucketRepository<TBucket> extends MongoRepository<TBucket> {

	protected $bucketLimit = 50;
	public IRedisClientsManager $redis;

	public function __construct()
	{
	
	}

	public function count(\MongoId $eventId)
	{
		$set = $this->redisCounterSet($eventId);
		$v = $this->redis->get($set);
		return is_int($v) ? $v : 0;
	}

	public function removeEntry(\MongoId $eventId, \MongoId $entityId, $onlyIds = false, $entityKey = 'entityId')
	{
		$query = $this->queryBuilder()
			->update()
			->upsert()
			->field($entityKey)->eq($eventId);

		if($onlyIds) {
			$query->field('data')->pull($entityId);
		} else {
			$query->field('data')->pull(array('_id' => $entityId));
		}

		return $query
			->getQuery()
			->execute();
	}

	public function addAll(array $ids, $entity, $entityKey = 'entityId')
	{
		foreach($ids as $eventId) {

			$this->add($eventId, $entity, $entityKey);
		}
	}

	public function add(\MongoId $eventId, $entity, $entityKey = 'entityId')
	{
		$set = $this->redisCounterSet($eventId);
		$count = $this->count($eventId);
		$this->redis->incr($set);

		$sequence = floor(($count - 1) / $this->bucketLimit);

		if($sequence < 0) {
			$sequence = 0;
		}

		$res = $this->queryBuilder()
			->update()
			->upsert()
			->field($entityKey)->eq($eventId)
			->field('position')->eq($sequence)
			->field('data')->push($entity)
			->getQuery()
			->execute();
	}

	public function isAttending(\MongoId $eventId, \MongoId $userId, $onlyIds = false)
	{
		$r = $this->queryBuilder()
			->find()
            ->hydrate(false)
			->field('entityId')->eq($eventId);

		if($onlyIds) {
			$r->field('data')->eq($userId);
		} else {
			$r->field('data.$._id')->eq($userId);
		}

			$query = $r->getQuery()
                ->getSingleResult();
		return !is_null($query) && count($query) > 0;
	}

	public function get($eventId, $limit = 21, $entityKey = 'entityId')
	{

		$res = $this->queryBuilder()
			->hydrate()
			->find()
			->field($entityKey)->eq($eventId)
			->sort('position', -1)
			->limit($limit)
			->getQuery()
			->getSingleResult();

		if(is_null($res)) {
			return array();
		}
		return $res->getData();
	}

	protected function redisCounterSet(\MongoId $id)
	{
		return 'bucket-count::' . (string)$id;
	}
}
