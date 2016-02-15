<?hh

namespace Pi\Odm;
use Pi\Host\HostProvider;
use Pi\EventManager;
use Pi\Interfaces\IEntity;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;
use Pi\Odm\Hydrator\MongoDBHydratorFactory;
use Pi\Odm\DocumentState;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\EntityPersistent;
use Pi\Odm\EntityPersistentBuilder;
use Pi\Odm\Query\UpdateQueryBuilder;
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\Query\QueryType;


/**
 * Unit Work holds all changes
 * Works as a queue for mongodb operations working with batches operations .
 * Consumes document persistences to do the database level operations
 */
class UnitWork
  implements IContainable{

  private $documentIdentifiers = Map{};

  private $originalDocumentData;

  private $documentChangeSets;
  /**
   * The (cached) states of any known documents.
   * Keys are object ids (spl_object_hash).
   *
   * @var array
   */
  private $documentStates;

  /**
   * A list of all pending document insertions.
   *
   * @var array
   */
  private $documentInsertions = array();

  /**
   * A list of all pending document updates.
   *
   * @var array
   */
  private $documentUpdates = array();

  /**
   * A list of all pending document upserts.
   *
   * @var array
   */
  private $documentUpserts = array();

  /**
   * Any pending extra updates that have been scheduled by persisters.
   *
   * @var array
   */
  private $extraUpdates = array();

  /**
   * A list of all pending document deletions.
   *
   * @var array
   */
  private $documentDeletions = array();

  /**
   * All pending collection deletions.
   *
   * @var array
   */
  private $collectionDeletions = array();

  /**
   * All pending collection updates.
   *
   * @var array
   */
  private $collectionUpdates = array();

  protected $originalData = array();

  protected $persisters = Map{};

  protected $persistenceBuilder;

  protected $identityMap = Map {};
  protected $hydratorFactory;

  public function __construct(
    protected OdmConfiguration $configuration,
    protected EventManager $eventManager,
    protected IEntityMetaDataFactory $classMetaDataFactory,
    public MongoManager $mongoManager)
  {

    $this->documentStates = Map{};

      $this->hydratorFactory = new MongoDBHydratorFactory(
        $configuration,
        $classMetaDataFactory,
        $eventManager,
        $this
        );

  }


  public function queryBuilder($className)
  {
    if(!is_string($className)) {
      $className = get_class($className);
    }
    return new QueryBuilder($this, $this->mongoManager, $className);
  }

  public function updateBuilder($entity)
  {
    return new UpdateQueryBuilder($entity, $this, $this->mongoManager);
  }

  public function hydratorFactory()
  {
    return $this->hydratorFactory;
  }

  public function configuration()
  {
    return $this->configuration;
  }

  public function ioc(IContainer $container)
  {


  }

  public function classMetaDataFactory()
  {
    return $this->classMetaDataFactory;
  }

  public function setDocumentPersistent(string $documentName, $persister)
  {
    $this->persisters[$documentName] = $persister;
  }

  public function performUpdates(EntityMetaData $entity, array $options = array())
  {
    $className = $entity->getName();
    $persister = $this->getDocumentPersister($className);

    foreach($this->documentUpdates as $oid => $document) {
      if(!$entity->isEmbeddedDocument() && !empty($this->documentChangeSets[$oid])) {
        $persister->update($document, $options);
      }
    }
  }

  public function performInsertions(EntityMetaData $entity, array $options = array())
  {
    $className = $entity->getName();
    $persister = $this->getDocumentPersister($className);
    $collection = $this->mongoManager->getDocumentCollection($className);

    $insertionsPerformed = array();

    foreach($this->documentInsertions as $oid => $insertion){
      if(get_class($insertion) === $className){
        $persister->addInsert($insertion);
        $insertionsPerformed[] = $insertion;
        unset($this->documentInsertions[$oid]);
      }
    }

    $persister->executeInserts($options);
    foreach($this->documentInsertions as $oid => $insertion){
      $id = $entity->getIdentifierValue($insertion);

      $oid = md5(serialize(($insertion)));
      $this->documentIdentifiers[$oid] = $id;
      $this->documentStates[$oid] = DocumentState::Managed;
      $this->originalDocumentData[$oid][$entity->identifier] = $id;

      $this->addToIdentityMap($insertion);

    }


    //hasPostPersistLifecycleCallbacks $class->invoke
  }

  public function getCommits($changeSet)
  {
    $newNodes = array();

    foreach ($changeSet as $document) {
        $className = get_class($document);

        $class = $this->mongoManager->getClassMetadata($className);
        $newNodes[] = $class;
    }

    return $newNodes;
  }

  public function commit($document = null, array $options = array())
  {
    // preFlush

    if ($document === null) {
            $this->computeChangeSets();
        } elseif (is_object($document)) {
            $this->computeSingleDocumentChangeSet($document);
        } elseif (is_array($document)) {
            foreach ($document as $object) {
                $this->computeSingleDocumentChangeSet($object);
            }
        }

     if ( ! ($this->documentInsertions ||
            $this->documentUpserts ||
            $this->documentDeletions ||
            $this->documentUpdates ||
            $this->collectionUpdates ||
            $this->collectionDeletions)
        ) {
            return; // Nothing to do.
        }

    $commits = $this->getCommits($this->documentInsertions);

    if ($this->documentInsertions) {
        foreach ($commits as $class) {
            if ($class->isEmbeddedDocument) {
                continue;
            }
            $this->performInsertions($class, $options);
        }
    }

    if ($this->documentUpdates) {
        foreach ($commits as $class) {
            $this->performUpdates($class, $options);
        }
    }

    // Clear up
       $this->documentInsertions =
       $this->documentUpserts =
       $this->documentUpdates =
       $this->documentDeletions =
       $this->extraUpdates =
       $this->documentChangeSets =
       $this->collectionUpdates =
       $this->collectionDeletions =
       $this->visitedCollections =
       $this->scheduledForDirtyCheck =
       $this->orphanRemovals =
       $this->hasScheduledCollections = array();
  }

  public function getPersistenceBuilder()
  {
    if ( ! $this->persistenceBuilder) {
        $this->persistenceBuilder = new EntityPersistentBuilder($this->mongoManager, $this);
    }
    return $this->persistenceBuilder;
  }

  public function getDocumentPersister($documentName)
  {
    if ( ! isset($this->persisters[$documentName])) {
        $class = $this->mongoManager->getClassMetadata($documentName);
        $pb = $this->getPersistenceBuilder();
        $this->persisters[$documentName] = new EntityPersistent($pb,$this->hydratorFactory, $this, $this->mongoManager, $class);
    }
    return $this->persisters[$documentName];
  }

  public function getDocumentChangeSet($document)
  {
    $oid = md5(serialize(($document)));

    if(isset($this->documentChangeSets[$oid])){

      return $this->documentChangeSets[$oid];
    } else {
      return array();
    }
  }

  public function computeSingleDocumentChangeSet($document)
  {
    $state = $this->documentStates->contains(md5(serialize(($document)))) ?
      $this->documentStates[md5(serialize($document))] : null;

    if($state !== DocumentState::Managed) {
      //throw new \Exception('Cant compute a unmaged document. removed not implemented yet');
    }

    $class = $this->mongoManager->getClassMetadata(get_class($document));
    if($state == DocumentState::Managed) {
      $this->persist($document);
    }

    // Compute changes for INSERTed and UPSERTed documents first. This must always happen even in this case.
    $this->computeScheduleInsertsChangeSets();
    // upserts
    // Only MANAGED documents that are NOT SCHEDULED FOR INSERTION, UPSERT OR DELETION are processed here.
    $oid = md5(serialize($document));

    if ( ! isset($this->documentInsertions[$oid])
        && ! isset($this->documentUpserts[$oid])
        && ! isset($this->documentDeletions[$oid])
        && isset($this->documentStates[$oid])
    ) {
        $this->computeChangeSet($class, $document);
    }
  }

  public function computeChangeSets()
  {
    $this->computeScheduleInsertsChangeSets();

    foreach($this->identityMap as $className => $documents) {
      $class = $this->mongoManager->getClassMetadata($className);
      // continue; embed classes, they should be computed only by the document itself that has the embed

      $documentsToProcess = $documents;

      foreach($documentsToProcess as $document){
        $oid = md5(serialize($document));
        if ( ! isset($this->documentInsertions[$oid])
            && ! isset($this->documentUpserts[$oid])
            && ! isset($this->documentDeletions[$oid])
            && isset($this->documentStates[$oid])
        ) {
            $this->computeChangeSet($class, $document);
        }
      }
    }

  }

  public function computeChangeSet($class, $document)
  {
    // preFlush
    $this->computeOrRecomputeChangeSet($class, $document);
  }

  public function computeOrRecomputeChangeSet($class, $document)
  {
    $oid = md5(serialize($document));
    $actualData = $this->getDocumentActualData($document);
    $isNewDocument = ! isset($this->originalDocumentData[$oid]);

    if ($isNewDocument) {
      // Document is either NEW or MANAGED but not yet fully persisted (only has an id).
      // These result in an INSERT.
      $this->originalDocumentData[$oid] = $actualData;

      $changeSet = array();
      foreach ($actualData as $propName => $actualValue) {
          $changeSet[$propName] = array(null, $actualValue);
      }
      $this->documentChangeSets[$oid] = $changeSet;
    } else {

      //  $originalData = $this->originalData[$oid];

      //  throw new \Exception('Not implemented for ODM');
    }
  }

  public function getDocumentActualData($document)
  {
    $class = $this->mongoManager->getClassMetadata(get_class($document));
    $actualData = array();
    foreach($class->reflFields as $name => $property){
      $mapping = $class->mappings()[$name];
      // if mapping notsaved continue; skip them
      $value = $property->getValue($document);
      $actualData[$name] = $value;
    }

    return $actualData;
  }
  public function computeScheduleInsertsChangeSets()
  {
    foreach ($this->documentInsertions as $document) {
        $class = $this->mongoManager->getClassMetadata(get_class($document));

        //if ($class->isEmbeddedDocument) {
         //   continue;
        //}

        $this->computeChangeSet($class, $document);
    }
  }

  private function getIdentifierFromClass($class, $document)
  {
     if ( ! $class->identifier) {
          $id = md5(serialize($document));
      } else {
          $id = $this->documentIdentifiers[md5(serialize($document))];
          $id = serialize($class->getDatabaseIdentifierValue($id));
      }
      return $id;
  }

  public function isPersisted($document)
  {
     $class = $this->mongoManager->getClassMetadata(get_class($document));
     $id = $this->getIdentifierFromClass($class, $document);

    return isset($this->identityMap[$class->name][$id]);
  }

  public function addToIdentityMap($document)
  {
          $class = $this->mongoManager->getClassMetadata(get_class($document));
      $id = $this->getIdentifierFromClass($class, $document);
      if (isset($this->identityMap[$class->name][$id])) {
          return false;
      }
      if(!isset($this->identityMap[$class->name])) {
        $this->identityMap[$class->name] = array();
      }
      $this->identityMap[$class->name][$id] = $document;

      //if ($document instanceof NotifyPropertyChanged) {
       //   $document->addPropertyChangedListener($this);
     // }

      return true;
  }

  /**
    * Calculates the size of the UnitOfWork. The size of the UnitOfWork is the
    * number of documents in the identity map.
    *
    * @return integer
    */
   public function size()
   {
       $count = 0;
       foreach ($this->identityMap as $documentSet) {
           $count += count($documentSet);
       }
       return $count;
   }

   public function createQueryBuilder($documentName = null)
  {
//      return $this->mongoManager->createQueryBuilder($documentName);
  }

  /**
    * @ignore
    */
   public function setOriginalDocumentData($document, array $data)
   {
       $this->originalDocumentData[md5(serialize($document))] = $data;
   }
  public function getChangesSet()
  {

  }

  public function getDocumentIdentifier(IEntity $document)
  {
    return isset($this->documentIdentifiers[md5(serialize($document))]) ?
            $this->documentIdentifiers[md5(serialize($document))] : null;
  }

  public function registerManaged($document, $id, array $data)
  {
    $oid = md5(serialize($document));
     $class = $this->mongoManager->getClassMetadata(get_class($document));

     if ( ! $class->identifier || $id === null) {
         $this->documentIdentifiers[$oid] = $oid;
     } else {
         $this->documentIdentifiers[$oid] = $class->getPHPIdentifierValue($id);
     }

     $this->documentStates[$oid] = 'STATE_MANAGED';
     $this->originalDocumentData[$oid] = $data;
     $this->addToIdentityMap($document);
  }

  public function getDocumentState($document, $default = null)
  {
    $oid = md5(serialize($document));
    $docName = get_class($document);

    if($this->documentStates->contains($oid))
      return $this->documentStates->get($oid);

    $class = $this->mongoManager->getClassMetadata($docName);

    if($default !== null){
      return $default;
    }

    $id = $class->getIdentifierObject($document);
    if($id === null){
      return DocumentState::NotCreated;
    }
    else {
      throw new \Pi\NotImplementedException('ODM is not ready yet for detached state');
    }
  }

  public function cascadePersist($document, array &$visited)
  {
    $class = $this->mongoManager->getClassMetadata(get_class($document));

  }

  public function persist($document)
  {
      $class = $this->mongoManager->getClassMetadata(get_class($document));
      $visited = array();
      $this->doPersist($document, $visited);
  }

  public function doPersist($document, array &$visited)
  {
    $oid = md5(serialize($document));

    $visited[$oid] = $document;
    $class = $this->mongoManager->getClassMetadata(get_class($document));

    $state = $this->getDocumentState($document);

    switch($state){
      case DocumentState::Managed:
//        throw new \Exception('not implemented');
      break;

      case DocumentState::NotCreated:
        $this->persistNotCreated($class, $document);
        break;

      default:
        $this->persistNotCreated($class, $document);
    }
  }

  public function persistNotCreated($class, $document)
  {

    $oid = md5(serialize($document));

    // event prePresist

    $upsert = false;

    if($class->hasIdentifier()){

      $idValue = $class->getIdentifierValue($document);
      $upsert = !$class->isEmbeddedDocument() && isset($idValue) && $idValue !== null;

      $this->documentIdentifiers[$oid] = $idValue;
    }

    /*
    if($class->getMultiTenantMode()){

      $appId = HostProvider::instance()->tryResolve('IRequest')->appId();
      $reflection = new \ReflectionProperty(get_class($document), $class->getMultiTenantField());
      $reflection->setAccessible(true);
      $reflection->setValue($document, $appId);
    }
    */

    $this->documentStates[$oid] = DocumentState::Managed;

    if($upsert){
      //throw new \Pi\NotImplementedException('ODM is not ready to handle upsert');
      $this->scheduleForInsert($class, $document);
    } else {

      $this->scheduleForInsert($class, $document);
    }
  }

  public function isScheduledForInsert($document)
  {
      return isset($this->documentInsertions[md5(serialize($document))]);
  }

  public function isDocumentScheduled($document)
  {
      $oid = md5(serialize($document));
      return isset($this->documentInsertions[$oid]) ||
          isset($this->documentUpserts[$oid]) ||
          isset($this->documentUpdates[$oid]) ||
          isset($this->documentDeletions[$oid]);
  }

  protected function assertCanScheduleForInsert(string $oid)
  {
    if (isset($this->documentUpdates[$oid])) {
        throw new \InvalidArgumentException("Dirty document can not be scheduled for insertion.");
    }
    if (isset($this->documentDeletions[$oid])) {
        throw new \InvalidArgumentException("Removed document can not be scheduled for insertion.");
    }
    if (isset($this->documentInsertions[$oid])) {
        throw new \InvalidArgumentException("Document can not be scheduled for insertion twice.");
    }
  }

  public function scheduleForInsert(EntityMetaData $class, $document)
  {

    $oid = md5(serialize($document));



    $this->assertCanScheduleForInsert($oid);

    $this->documentInsertions[$oid] = $document;

    if(isset($this->documentIdentifiers[$oid])){

      $this->addToIdentityMap($document);
    } else {

    }
  }

  public function loadCollection($collection)
  {
    $this->getDocumentPersister(get_class($collection->getOwner()))->loadCollection($collection);
  }

  public function getEmbedDocument($className, $data, &$hints = array(), $manage = false)
  {
    return $this->getDocument($className, $data, $hints, $manage);
  }

  public function getDocument($className, $data, &$hints = array(), $manage = false)
  {
    $class = $this->mongoManager->getClassMetadata($className);
    $id = null;

    if(array_key_exists('_id', $data)) {
      $id = $class->getDatabaseIdentifierValue($data['_id']);
    } else if(array_key_exists('id', $data)) {
      $id = $class->getDatabaseIdentifierValue($data['id']);
    }

    $serializedId = serialize($id);

    if (isset($this->identityMap[$class->name][$serializedId])) {

    }

    $document = $class->newInstance();

    if($manage){
      $this->registerManaged($document, $id, $data);
      $oid = md5(serialize($document));
      $this->documentStates[$oid] = 'STATE_MANAGED';
      $this->identityMap[$class->name][$serializedId] = $document;
      $this->originalDocumentData[$oid] = $data;
    }

    if(!is_array($data)) {
    }
    $this->hydratorFactory->hydrate($document, $data, $hints);
    return $document;
  }

  public function isScheduledForUpdate($document)
  {
    return isset($this->documentUpdates[md5(serialize($document))]);

  }

  public function getOrCreateDocument($className, $data, &$hints = array(), $document = null)
  {
    $class = $this->mongoManager->getClassMetadata($className);

    // @TODO figure out how to remove this
    $discriminatorValue = null;
    if (isset($class->discriminatorField, $data[$class->discriminatorField])) {
        $discriminatorValue = $data[$class->discriminatorField];
    } elseif (isset($class->defaultDiscriminatorValue)) {
        $discriminatorValue = $class->defaultDiscriminatorValue;
    }

    if ($discriminatorValue !== null) {
        $className = isset($class->discriminatorMap[$discriminatorValue])
            ? $class->discriminatorMap[$discriminatorValue]
            : $discriminatorValue;

        $class = $this->mongoManager->getClassMetadata($className);

        unset($data[$class->discriminatorField]);
    }

    $id = $class->getDatabaseIdentifierValue($data['_id']);
    $serializedId = serialize($id);

    if (isset($this->identityMap[$class->name][$serializedId])) {
        $document = $this->identityMap[$class->name][$serializedId];
        $oid = md5(serialize($document));
        if ($document instanceof Proxy && ! $document->__isInitialized__) {
            $document->__isInitialized__ = true;
            $overrideLocalValues = true;
            /*
            if ($document instanceof NotifyPropertyChanged) {
                $document->addPropertyChangedListener($this);
            }
             */
        } else {
            $overrideLocalValues = ! empty($hints[QueryType::HINT_REFRESH]);
        }
        if ($overrideLocalValues) {
            $data = $this->hydratorFactory->hydrate($document, $data, $hints);
            $this->originalDocumentData[$oid] = $data;
        }
    } else {
        if ($document === null) {
            $document = $class->newInstance();
        }
        $this->registerManaged($document, $id, $data);
        $oid = md5(serialize($document));
        $this->documentStates[$oid] = 'STATE_MANAGED';
        $this->identityMap[$class->name][$serializedId] = $document;
        $data = $this->hydratorFactory->hydrate($document, $data, $hints);
        $this->originalDocumentData[$oid] = $data;
    }
    return $document;
  }
}
