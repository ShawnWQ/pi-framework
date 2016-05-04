<?hh

namespace Pi\Odm;

use Pi\Odm\Interfaces\ICollectionRepository;
use Pi\Odm\Interfaces\IUpdateQueryBuilder;
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\Query\UpdateQueryBuilder;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\Odm\Events\UpdateEventArgs;
use Pi\EventManager;
use Pi\Host\HostProvider;
use Pi\Odm\UnitWork;
use Pi\Redis\Interfaces\IRedisClient;

class MongoRepository<T>
  implements ICollectionRepository<T>, IContainable {


    public $mongo;

    protected $collection;

    public EventManager $eventManager;


    protected $classMetadata;

    public UnitWork $unitWork;



    public function setClassMetadata($classMetadata)
    {
      $this->classMetadata = $classMetadata;
    }

    public function ioc(IContainer $ioc)
    {

      $this->unitWork = $ioc->get('UnitWork');
      $this->eventManager = $ioc->get('EventManager');
        $this->mongoManager = $ioc->get('MongoManager');
      $this->collection = $this->mongoManager->getDocumentCollection($this->classMetadata->getName());

    }

    protected function incrField(\MongoId $entityId, string $fieldName, $incrBy)
    {
      return $this->queryBuilder()
        ->update()
        ->field('_id')->eq($entityId)
        ->field($fieldName)->incr($incrBy)
        ->getQuery()
        ->execute();
    }

    public function incrViews(\MongoId $entityId, $incrBy = 1)
    {
      $entity = $this->classMetadata->getName();
      $this->redis->incr($entity . (string)$entityId, $incrBy);
    }

    public function getViews(\MongoId $entityId)
    {
      $entity = $this->classMetadata->getName();
      return $this->redis->get($entity . (string)$entityId);
    }
    public function incrLikes(\MongoId $entityId, $incrBy = 1)
    {
      return $this->incrField($entityId, 'likesCount', $incrBy);
    }
    public function incrComment(\MongoId $entityId, $incrBy = 1)
    {
      return $this->incrField($entityId, 'commentsCount', $incrBy);
    }

    public function commit($document = null, array $options = array())
    {
      $this->unitWork->commit($document, $options);
    }

    public function insert(&$entity, $commit = true)
    {
      $this->unitWork->persist($entity);
      if($commit) {
        $this->commit();
      }
    }

    public function findAndUpdate($query, $update, array $fields = array(), $sort = null, $upsert = true)
    {

      $doc = $this->collection->findAndUpdate($query, $update, $fields, $sort, $upsert);
      if(is_null($doc)) {
        return null;
      }

      $hydrated = $this->unitWork->getDocument($this->classMetadata->getName(), $doc);
      return $hydrated;
    }

    public function exists(\MongoId $id) : bool
    {
      $doc = $this->collection->findOneById($id);
      return !is_null($doc);
    }

    public function get( $id) : T
    {
      $doc = $this->collection->findOneById($id);
        if(is_null($doc)) {
            return null;
        }
      $hydrated = $this->unitWork->getDocument($this->classMetadata->getName(), $doc);
      return $hydrated;
    }

    public function getAs($id, string $className)
    {
        $doc = $this->collection->findOneById($id);
        if(is_null($doc)){
          return null;
        }
        $class = $this->mongoManager->getClassMetadata($className);

        if(is_null($class) || !$class) {
          throw new \Exception('The class ' . $className . ' couldnt be mapped');
        }

        return $this->unitWork->getDocument($className, $doc);
    }

    public function queryBuilder(?string $entity = null)
    {
      if(is_null($entity)) {
        $entity = $this->classMetadata->getName();
      }

      return $this->unitWork->queryBuilder($entity);
    }

    public function updateQuery($instance)
    {
      return $this->unitWork->updateBuilder($instance);
    }

  	public function update(UpdateQueryBuilder $builder) : IDataOperationResult
    {
      if($this->eventManager->has(Events::PreUpdate)){
        $this->eventManager->dispatch(Events::PreUpdate, new UpdateEventArgs($this, $builder));
      }
      $query = $builder->getQuery();
      $result = $this->doUpdate($query);

      if($this->eventManager->has(Events::PostUpdate)) {
        $this->eventManager->dispatch(Events::PostUpdate, new EventArgs($this, $result));
      }

      return $result;
    }

    public function doUpdate($query) : IDataOperationResult {
      $query->execute();
    }

  	public function remove($id)
    {
      return $this->queryBuilder()
        ->remove()
        ->field('_id')->eq($id)
        ->getQuery()
        ->execute();
    }

    public function count()
    {
      return $this->queryBuilder()
      ->getQuery()
        ->count();
    }
  	public function find() : array
    {
      throw new Pi\NotImplementedException();
    }
  }
