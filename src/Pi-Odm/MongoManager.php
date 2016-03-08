<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;
use Pi\Odm\Query\UpdateQueryBuilder;
use Pi\Odm\Mapping\InheritanceType;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class MongoManager implements IContainable{

	public function ioc(IContainer $container){}

	protected $documentCollections;

	protected $documentDatabases;

	public function __construct(
		protected IEntityMetaDataFactory $classMetadataFactory,
		protected OdmConfiguration $configuration,
		protected DatabaseManager $databaseManager)
	{
		$this->documentCollections = array();
		$this->documentDatabases = array();
	}

	public function getClassMetadata(string $className)
	{
  		return $this->classMetadataFactory->getMetadataFor(ltrim($className, '\\'));
	}

  public function getDocumentDatabase($className)
	{
		$className = ltrim($className, '\\');
    if (isset($this->documentDatabases[$className])) {
        return $this->documentDatabases[$className];
    }
    $metadata = $this->classMetadataFactory->getMetadataFor($className);
    $db = $metadata->getDatabase();
    $db = $db ? $db : $this->configuration->getDefaultDb();
    $db = $db ? $db : 'pi';
    $this->documentDatabases[$className] = $this->databaseManager->selectDatabase($db);

    return $this->documentDatabases[$className];
	}

	public function getDocumentCollection(string $className)
	{
		$className = ltrim($className, '\\');

    $metadata = $this->classMetadataFactory->getMetadataFor($className);
    $collectionName = $metadata->getCollection();

    if ( ! $collectionName) {
        throw new \Exception('documentNotMappedToCollection: ' . $className);
    }


		if(!is_null($metadata->getInheritanceType()) && is_null($metadata->getCollection(9))) { // Document is inherited
			$class = $metadata->getReflectionClass();
			$parentName = $class->getParentClass()->getName();
			$parent = $this->getDocumentCollection($className);
			if($parent->isMappedSuperclass() && $metadata->getInheritanceType() === InheritanceType::Single) {
				$className = $parentName; // the collection returned is the superclass single
			}
		}


    if (!isset($this->documentCollections[$className])) {
        $db = $this->getDocumentDatabase($className);

        $this->documentCollections[$className] = $metadata->isFile()
            ? $db->getGridFS($collectionName)
            : $db->selectCollection($collectionName);
    }

    $collection = $this->documentCollections[$className];

    if ($metadata->slaveOkay !== null) {
        $collection->setSlaveOkay($metadata->slaveOkay);
    }

    return $this->documentCollections[$className];
	}

}
