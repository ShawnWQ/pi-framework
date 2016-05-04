<?hh

namespace Pi\Odm;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\Hydrator\MongoDBHydratorFactory;
use Pi\Interfaces\IEntity;
use Pi\Odm\EntityPersistentBuilder;
use Pi\Odm\Query\QueryType;

/**
 * This object is responsible for persist an EntityMetaData
 */
class EntityPersistent {


  protected $collection;

  protected $queuedInsertions = array();

  public function __construct(
    protected EntityPersistentBuilder $persistentBuilder,
    protected MongoDBHydratorFactory $hydratorFactory,
    protected UnitWork $unitWork,
    protected MongoManager $mongoManager,
    protected EntityMetaData $class)
  {
    $this->collection =  $this->mongoManager->getDocumentCollection($class->getName());
  }

  public function update($document, $options = array())
  {
    $id = $this->unitWork->getDocumentIdentifier($document);

  }

  public function addInsert($document)
  {
    $this->queuedInsertions[md5(serialize($document))] = $document;
  }
  public function isQueuedForInsert($document)
  {
      return isset($this->queuedInsertions[md5(serialize($document))]);
  }
  /**
   * @return array containing the insertions
   */
  public function executeInserts($options = array())
  {
    if(!$this->queuedInsertions){
      return;
    }

    $insertions = array();
    foreach($this->queuedInsertions as $oid => $document){
      $data = $this->persistentBuilder->prepareInsertData($document);
      $insertions[$oid] = $data;
    }

    if($insertions){
      try {
        $res = $this->collection->batchInsert($insertions, $options);
      } catch(\MongoException $ex){
        $this->queuedInsertions = array();
        throw $ex;
      }
    }

    $this->queuedInsertions = array();
    return $insertions;
  }

  /**
   * Refreshes a managed document.
   *
   * @param array $id The identifier of the document.
   * @param object $document The document to refresh.
   */
  public function refresh($id, IEntity $document)
  {
      $class = $this->mongoManager->getClassMetadata(get_class($document));
      $data = $this->collection->findOne(array('_id' => $id));
      $data = $this->hydratorFactory->hydrate($document, $data);
      $this->unitWork->setOriginalDocumentData($document, $data);
  }

  public function query(array $criteria, $document = null, array $hints = array(), $lockMode = 0, ?array $sort = null)
  {
    $cursor = $this->collection->find($criteria);
    // sort

    return $cursor;
  }

  public function get(array $criteria, $document = null, $hints = array())
  {
    $result = $this->collection->find($criteria);
    return $this->createDocument($result, $document, $hints);
  }

  private function createDocument($result, $document = null, $hints = array())
  {
    if ($document !== null) {
        $hints[QueryType::HINT_REFRESH] = true;
        $id = $this->class->getPHPIdentifierValue($result['_id']);
        $this->unitWork->registerManaged($document, $id, $result);
    }

    return $this->unitWork->getOrCreateDocument($this->class->name, $result, $hints, $document);
  }

  public function delete(IEntity $document)
  {
    $id = $this->unitWork->getDocumentIdentifier($document);
  }
}
