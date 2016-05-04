<?hh

namespace Pi\ServiceInterface\Data;

use Pi\Odm\MongoRepository;
use SpotEvents\ServiceModel\Types\EventAttendant;
use Pi\Odm\BucketCollection;
use Pi\ServiceModel\Types\InboxMessage;
use Pi\Redis\Interfaces\IRedisClientsManager;

class UserInboxRepository extends MongoRepository<TBucket> {

	protected $bucketLimit = 50;

	public IRedisClientsManager $redis;

	public function count(\MongoId $userId)
	{
		$set = $this->redisCounterSet($userId);
		$val = $this->redis->get($set);
		return is_int($val) ? 0 : $val;
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
			$query->field('messages')->pull(array('_id' => $entityId));
		}

		return $query
			->getQuery()
			->execute();
	}

	public function add(\MongoId $fromId, \MongoId $toId, InboxMessage $message)
	{
		$set = $this->redisCounterSet($fromId);
		$count = $this->count($fromId);
		$this->redis->incr($set);

		$sequence = floor(($count - 1) / $this->bucketLimit);
		
		if($sequence < 0) {
			$sequence = 0;
		}

		$res = $this->queryBuilder()
			->update()
			->upsert()
			->field('fromId')->eq($fromId)
			->field('toId')->eq($toId)
			->field('position')->eq($sequence)
			->field('messages')->push($message)
			->getQuery()
			->execute();
	}

	/**
	 * Get sent and received messages
	 */
	public function getConversation(\MongoId $fromId, \MongoId $toId, $limit = 21)
	{
		
		$buckets = $this->queryBuilder()
			->hydrate()
			->find()
			->field('fromId')->eqOr($fromId)
			->field('toId')->eqOr($fromId)
			->endOr()
			->sort('when', -1)
			->limit($limit)
			->getQuery()
			->execute();
			
		if(is_null($buckets)) {
			return array();
		}

		$res = array();
		foreach($buckets as $bucket) {
			$msgs = $bucket->messages();
			foreach($msgs as $msg) {
			$res[] = $msg;	
			}
			
		}
		
		return $res;
	}

	protected function redisCounterSet(\MongoId $id)
	{
		return 'bucket-count::' . (string)$id;
	}
}