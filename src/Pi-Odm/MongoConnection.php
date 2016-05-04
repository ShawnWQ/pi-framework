<?hh

namespace Pi\Odm;

use Pi\EventManager;
use Pi\Interfaces\IDatabaseConnection;
use Pi\Odm\MongoDatabase;
use Pi\Odm\MongoTransaction;
use Pi\Odm\MongoCommand;
use Pi\Odm\MongoCommandBuilder;
use Pi\Odm\MongoUpdateQueryBuilder;
use Pi\Odm\MongoConnectionTunnel;
use Pi\Odm\Interfaces\IDbTransaction;
use Pi\Odm\Interfaces\IDbConnection;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;
use Pi\NotImplementedException;
use Pi\Odm\Query\UpdateQueryBuilder;
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\MongoEntity;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\Odm\Repository\RepositoryFactory;

class MongoConnection implements IDbConnection, IContainable{


  protected $connectionString;

  protected ?MongoTransaction $transaction = null;

  protected $dbTunnel;

  protected $ioc;

  protected $documentManager;

  protected $unitWork;

  public $repositories = Map{};

  protected $timeout = 30;

  protected $database;

  public function __construct(
    protected \MongoClient $client,
    protected EventManager $eventManager,
    protected IEntityMetaDataFactory $classMetaDataFactory)
  {
    //$this->connectionString = $client->getConnections()[0]["hash"];
  }


  public function ioc(IContainer $container)
  {
  }

  public function insert<T>(T $entity)
  {
    $repo = $this->getRepository($entity);
    $repo->insert($entity);
    return $entity;
  }

  public function selectById($entity, \MongoId $id)
  {
    $repo = $this->getRepository($entity);
    return $repo->get($id);
  }

  public function select($query = null)
  {

  }

  /**
   * Save the Entity instance
   * If the document is already managed by UnitWork, the changes are performed from changeSets
   * If it's managed, the update is done only on set properties from the entity. Properties set to null will be unset
   * @param  MongoEntity $entity [description]
   * @return [type]              [description]
   */
  public function save($entity)
  {
    //$builder = new UpdateQueryBuilder($entity, $this->documentManager);
    //$query = $builder->createQuery();
    //$query->execute();

  }

  public function update($entity)
  {
    $builder = new QueryBuilder($this->documentManager, get_class($entity));
    $builder->update();
    return $builder;
  }

  public function updateById(\MongoId $id, MongoUpdateQueryBuilder $builder)
  {
    $commands = $builder->getQuery();
    return $this->client->update(array('_id' => $id));
  }
  public function deletebyId($id)
  {

  }

  public function flush()
  {

  }

  public function getMongoClient()
  {
    return $this->client;
  }

  public function tunnel()
  {
    return $this->dbTunnel;
  }

  public function getConnectionString()
  {
    return $this->connectionString;
  }
  public function getConnectionTimeout()
  {
    return $this->timeout;
  }
  public function getDatabase()
  {
    return $this->database;
  }
  public function getState()
  {
    throw new NotImplementedException();
  }

  private function assertTransactionNotCreated() : void
  {
    if(!is_null($this->transaction)){
      throw new \Exception('The MongoTransaction doesnt support parallel transactions. Transaction already exists');
    }
  }

  private function setTransaction($level = null)
  {
    $this->transaction = new MongoTransaction($this, $level);
  }

  /**
   * Begins a database transaction.
   * @return IDbTransaction
   */
  public function beginTransaction()
  {
    $this->assertTransactionNotCreated();
    $this->setTransaction();
    return $this->transaction;
  }
  public function beginIsolatedTransaction($level)
  {
    $this->assertTransactionNotCreated();
    $this->setTransaction($level);
    return $this->transaction;
  }

  public function dropTransaction()
  {
    $this->transaction = null;
  }

  /**
   * Changes the current database for an open Connection object
   */
  public function changeDatabase()
  {
    throw new NotImplementedException();
  }

  public function close()
  {
    throw new NotImplementedException();
  }

  /**
   * Create and return an command associated with the connection
   */
  public function createCommand()
  {

    throw new NotImplementedException();
  }
  /**
   * Performs application-defined tasks associated with freeing, releasing, or resetting unmanaged resources. (Inherited from IDisposable.)
   * @return [type] [description]
   */
  public function dispose()
  {
    throw new NotImplementedException();
  }

  private function getRepository($entity)
  {
    if(!$this->repositories->contains(get_class($entity))){
      $this->repositories[get_class($entity)] = $this->unitWork->getRepository(get_class($entity));
    }
    return $this->repositories[get_class($entity)];
  }
}
