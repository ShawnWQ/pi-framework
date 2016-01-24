<?hh
namespace Pi\Odm\Query;
use Pi\Odm\MongoManager;
use Pi\Odm\UnitWork;

class QueryBuilder {

    protected $current;
    protected $commands = [];
    protected $type;
    protected $select;
    protected $hydrate;
    protected $refresh = false;
    protected $upsert;
    protected $class;
    protected $collection;
    protected $exprQuery = array();
    protected $primers = array();
    protected $requireIndexes;
    /**
     * The "new object" array containing either a full document or a number of
     * atomic update operators.
     *
     * @see docs.mongodb.org/manual/reference/method/db.collection.update/#update-parameter
     * @var array
     */
    protected $newObj = array();

    protected $multi;

    protected $query;

    public function __construct(
      protected UnitWork $unitWork, protected MongoManager $mongoManager,
      protected $documentName = null)
    {
      $this->query = array('type' => QueryType::TYPE_FIND);
      $this->select = array();
      if ($this->documentName !== null) {
          $this->setDocument($this->documentName);
      }
    }

  
    public function hydrate($hydrate = true)
    {
        $this->hydrate = $hydrate;
        return $this;
    }

    public function limit($value)
    {
      $this->query['limit'] = $value;
      return $this;
    }

    public function skip($value)
    {
      $this->query['skip'] = $value;
      return $this;
    }

    public function sort($key, $order)
    {
      $this->query['sort'][$key] = $order;
      return $this;
    }

    public function multi($enable = true) : void
    {
        $this->multi = true;
        $this->query['multi'] = $true;
    }
    /**
     * Set the "new" option for a findAndUpdate command.
     *
     * @param boolean $bool
     * @return self
     */
    public function returnNew(bool $bool = true)
    {
        $this->query['new'] = $bool;
        return $this;
    }

    public function refresh(bool $bool = true)
    {
        $this->refresh = $bool;
        return $this;
    }

    public function select($fieldName)
    {
        if ( ! isset($this->query['select'])) {
            $this->query['select'] = array();
        }

        $fieldNames = is_array($fieldName) ? $fieldName : func_get_args();

        foreach ($fieldNames as $fieldName) {
            $this->query['select'][$fieldName] = 1;
        }

        return $this;
    }

    public function findAndUpdate()
    {
      $this->query['type'] = QueryType::TYPE_FIND_AND_UPDATE;
      $this->type = QueryType::TYPE_FIND_AND_UPDATE;
      return $this;
    }

    public function find($document = null)
    {
        $this->setDocument($document);
        $this->type = QueryType::TYPE_FIND;
        return $this;
    }

    public function update()
    {
        $this->query['type'] = QueryType::TYPE_UPDATE;
        $this->type = QueryType::TYPE_UPDATE;
        return $this;
    }
    public function getCommand()
    {
    }

    protected $currentIsOr = true;

    protected $currentOr;

    public function eqOr($value)
    {
        $this->currentOr[$this->current] = $value;

        $this->currentIsOr = true;
        unset($this->current);
        return $this;
    }

    public function endOr()
    {

        $this->exprQuery['$or'][] = $this->currentOr;

        return $this;
    }

    public function field($fieldName)
    {
        $this->current = $fieldName;
        return $this;
    }

    public function push($entry)
    {
      $e = $entry instanceof \JsonSerializable ? json_encode($entry) : $entry;

      if(!is_scalar($entry) && !is_array($e) && get_class($entry) !== 'MongoId' && get_class($entry) !== '\MongoId') {
        $data = $this->unitWork->getPersistenceBuilder()->prepareBuildData($entry);
        $this->newObj['$push'][$this->current] = $data;
      } else {
        $this->newObj['$push'][$this->current] = $entry;
      }
        unset($this->current);
        return $this;
    }

    public function upsert($value = true)
    {
      $this->upsert = $value;
      return $this;
    }



    public function remove()
    {
      $this->query['type'] = QueryType::TYPE_REMOVE;
      $this->type = QueryType::TYPE_REMOVE;
      return $this;
    }

    public function incr($total = 1)
    {
      $this->newObj['$inc'][$this->current] = $total;
      unset($this->current);
      return $this;
    }
    public function decr($total = 1)
    {
    }

    public function addOr($expression)
    {
        $this->exprQuery['$or'][] = $expression instanceof Expr ? $expression->getQuery() : $expression;
        return $this;
    }

    public function eq($value)
    {
        $this->exprQuery['$eq'][$this->current] = $value;
        unset($this->current);
        return $this;
    }

    public function in($value)
    {
        $this->exprQuery['$in'][$this->current] = $value;
        unset($this->current);
        return $this;
    }

    public function pull($value)
    {
        $this->exprQuery['$pull'][$this->current] = $value;
        unset($this->current);
        return $this;
    }

    public function setDefault($atomic = true)
    {
        
    }

    public function set($value, $atomic = true)
    {
        if ($atomic) {
            $this->newObj['$set'][$this->current] = $value; //;= array($this->current => $value);//
            unset($this->current);
            return $this;
        }
        if (strpos($this->current, '.') === false) {
            $this->newObj[$this->current] = $value;
            return $this;
        }

        $this->exprQuery['$eq'] = array($this->current, $value);
        unset($this->current);
        $keys = explode('.', $this->current);
        $current = &$this->newObj;
        foreach ($keys as $key) {
            $current = &$current[$key];
        }
        $current = $value;
        return $this;
    }

    public function getQuery(array $options = array())
    {
        $documentPersister = $this->unitWork->getDocumentPersister($this->class->name);
        

        $query = $this->query;
        $query['query'] = $this->exprQuery;
        if ($this->query['type'] === QueryType::TYPE_MAP_REDUCE) {
            $this->hydrate = false;
        }
        $query['newObj'] = $this->newObj;
        if (isset($query['sort'])) {
            //$query['sort'] = $documentPersister->prepareSortOrProjection($query['sort']);
        }

        if($this->multi) {
            $options['multi'] = $this->multi;
        }
        if($this->upsert) {
          $options['upsert'] = $this->upsert;
        }
        $documentPersister = $this->unitWork->getDocumentPersister($this->class->name);

        $query = $this->query;

        $query['query'] = $this->exprQuery;


        if ($this->query['type'] === QueryType::TYPE_MAP_REDUCE) {
            $this->hydrate = false;

        }
        $query['newObj'] = $this->newObj;

        if (isset($query['sort'])) {
            //$query['sort'] = $documentPersister->prepareSortOrProjection($query['sort']);
        }

        return new QueryExecutor(
                    $this->unitWork,
                    $this->mongoManager,
                    $this->class,
                    $this->collection,
                    $query,
                    $options,
                    $this->hydrate,
                    $this->refresh,
                    $this->primers,
                    $this->requireIndexes
                );
    }

    protected function setDocument($documentName)
    {
        if(is_array($documentName)) {

        } else if($documentName !== null) {
            $this->collection = $this->mongoManager->getDocumentCollection($documentName);
            $this->class = $this->mongoManager->getClassMetadata($documentName);
        }
    }
}
