<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceInterface\Data\UserFeedItemRepository;
use Pi\ServiceInterface\Data\FeedActionRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceModel\Types\FeedAction;
use Pi\ServiceModel\Types\UserFeedItem;
use Pi\Interfaces\IFeed;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class UserFeedBusiness implements IContainable {

	public function __construct(
		public FeedActionRepository $feedRepo,
		public UserFeedItemRepository $itemRepo,
		public UserRepository $userRepo
		)
	{

	}

	public function ioc(IContainer $ioc)
	{

	}


	public async function createAsync(\MongoId $userId, IFeed $entity) : Awaitable<bool>
	{
		return $this->create($userId, $entity);
	}

	public function createPublic(FeedAction $feed)
	{
		$data = $this->userRepo
			->queryBuilder()
			->find()
			->hydrate(false)
			->select('_id')
			->limit(5000)
			->getQuery()
			->toArray();
		$ids = array();
		foreach($data as $d) {
			$ids[] = $d['_id'];
		}

		return $this->createAll($ids, $feed);
	}

	public function create(\MongoId $userId, FeedAction $feed)
	{
		$item = new UserFeedItem();
		$this->feedRepo->insert($feed);
		$item->actionId($feed->id());
		$item->setUserId($userId);
		$this->itemRepo->insert($item);
		return $item;
	}

	public function createAll(array $ids, FeedAction $feed)
	{
		$this->feedRepo->insert($feed);

		foreach($ids as $id) {
		  $item = new UserFeedItem();
			$item->actionId($feed->id());
			$item->setUserId($id);
			$this->itemRepo->insert($item);
		}
		return true;
	}

	public function get($userId, $skip = 0, $take = 20)
	{
		$data = $this->itemRepo
			->queryBuilder()
			->find()
			->hydrate(false)
			->select('userId')
			->getQuery()
			->toArray();
		$ids = array();
		foreach($data as $d) {
			$ids[] = $d['userId'];
		}

		$feeds = $this->feedRepo
			->queryBuilder()
			->find()
			->hydrate(true)
			->field('actorId')->in($ids)
			->getQuery()
			->execute();

		return $feeds;
	}

	public function count($userId)
	{
		return $this->feedRepo->count($userId);
	}
}
