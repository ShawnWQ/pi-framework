<?hh

namespace Pi\Odm\Repository;
use Pi\Odm\MongoManager;
use Pi\Odm\UnitWork;
use Pi\Odm\MongoRepository;
use Pi\EventManager;
use Pi\Host\HostProvider;
use Pi\Odm\MongoConnection;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;


class RepositoryFactory implements IContainable {

  protected $repositories;

  public function __construct(
    protected MongoManager $mongoManager, 
    protected EventManager $eventManager)
  {
    $this->repositories = Map{};
  }
  protected $ioc;

  public function ioc(IContainer $ioc)
  {
    $this->ioc = $ioc;
  }

  public function getRepository($documentName)
  {
    
    // Use the default repository, otherwise was already registered

    if(!$this->repositories->contains($documentName)){
      $this->repositories[$documentName] = $this->createRepository($documentName);
    }

    $repo = $this->repositories[$documentName];
    $repo->ioc(HostProvider::instance()->container());

    return $repo;
  }

  public function registerRepository($repository, $entity)
  {
    $metadata = $this->mongoManager->getClassMetadata($entity);
    $repo = new $repository($metadata);
    $this->repositories[$entity] = $repository;

  }

  protected function createRepository($documentName)
  {

    $metadata            = $this->mongoManager->getClassMetadata($documentName);

    $repositoryClassName = 'Pi\Odm\MongoRepository';

    $repo = new $repositoryClassName();
    $repo->ioc($this->ioc);
    $repo->setClassMetadata($metadata);
  }
}
