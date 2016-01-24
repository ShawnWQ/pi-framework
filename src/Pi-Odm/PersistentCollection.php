<?hh

namespace Pi\Odm;
use Pi\Odm\UnitWork;
use Pi\Odm\MongoManager;

class PersistentCollection  {

	/**
     * A snapshot of the collection at the moment it was fetched from the database.
     * This is used to create a diff of the collection at commit time.
     *
     * @var array
     */
	private $snapshot = array();

	private $owner;

	private $mapping;

	private $isDirty = false;

	private $initialized = true;

	/**
	 * The raw mongo data that will be used to initialize this collection.
	 * @var array
	 */
	private $mongoData = array();

	/**
     * Any hints to account for during reconstitution/lookup of the documents.
     *
     * @var array
     */
    private $hints = array();

    protected $typeClass;

    public function __construct(private $collection, private UnitWork $unitWork, private MongoManager $mongoManager)
    {

    }

    /**
     * Initializes the collection by loading its contents from the database
     * if the collection is not yet initialized.
     */
    public function initialize()
    {
    	if($this->initialized || ! $this->mapping) {
    		return;
    	}

    	$newsObjs = array();
    	if($this->isDirty) {
    		$newsObjs = $this->collection->toArray();
    	}
    	$this->collection->clear();
    	$this->unitWork->loadCollection($this);

    	// Reattach any NEW objects added through add()
    	if($newsObjs){
    		foreach ($newsObjs as $key => $obj) {
    			$this->collection->add($obj);
    		}
    		$this->isDirty = true;
    	}

    	$this->mongoData = array();
    	$this->initialized = true;
    }

    public function setOwner($document, array $mapping)
    {
        $this->owner = $document;
        $this->mapping = $mapping;
    }

    public function getOwner()
    {
    	return $this->owner;
    }

    public function mapping()
    {
    	return $this->mapping;
    }

    public function takeSnapshot()
    {
		$this->snapshot = $this->collection->toArray();
        $this->isDirty = false;
    }

    public function setDirty($boolean)
    {
    	$this->isDirty = $boolean;
    }

    public function getTypeClass()
    {
    	return $this->typeClass;
    }

    public function setInitialized($value)
    {
    	$this->initialized = $value;
    }

    public function isInitialized()
    {
    	return $this->initialized;
    }

    public function first()
    {
    	$this->initialize();
    	$this->collection->first();
    }

    
}