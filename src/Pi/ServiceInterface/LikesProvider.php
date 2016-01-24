<?hh

namespace Pi\ServiceInterface;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\ServiceInterface\Data\LikesRepository;
use Pi\Host\HostProvider;

class LikesProvider implements IContainable {

    public LikesRepository $likesRepo;


    public function __construct(protected string $name)
    {

    }

    public function ioc(IContainer $ioc)
    {

    }

    public function get(\MongoId $id)
    {
        return $this->likesRepo->get($id);
    }


	public function add(\MongoId $entityId, \MongoId $userId, ?string $namespace = null) : bool
	{
        if($this->hasLiked($entityId, $userId)) {
            return false;
        }
        $this->likesRepo->add($entityId, $userId);
        if(!is_null($namespace)) {
            $repo = HostProvider::instance()->container()->getRepository($namespace);
        }
        return true;
	}

	public function count(\MongoId $entityId)
	{
        return $this->likesRepo->count($entityId);
	}

	public function hasLiked(\MongoId $entityId, \MongoId $userId)
	{
        return $this->likesRepo->isAttending($entityId, $userId, true);
	}
}
