<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceInterface\Data\AppFeedRepository;
use Pi\ServiceModel\Types\AppFeed;

class ApplicationFeedBusiness {

	public function __construct(protected AppFeedRepository $feedRepo)
	{

	}

	public async function createAsync(\MongoId $appId, AppFeed $entity) : Awaitable<bool>
	{
		return $this->create($appId, $entity);
	}

	public function create(\MongoId $appId, AppFeed $entity)
	{
		$this->feedRepo->add($appId, $entity, 'appId');
		return true;
	}

	public function get($appId, $skip = 0, $take = 1)
	{
		return $this->feedRepo->get($appId, $take, 'appId', $skip);
	}

	public function count($appId)
	{
		return $this->feedRepo->count($appId);
	}
}