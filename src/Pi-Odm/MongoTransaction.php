<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IDbTransaction;
use Pi\Odm\Interfaces\IDbConnection;
use Pi\Odm\MongoConnection;

class MongoTransaction

  implements IDbTransaction {

    protected bool $isOpen;

    protected $disposed = false;
    protected $connection;

    public function __construct(MongoConnection $connection, protected ?string $isolationLevel = 'default')
    {
      $this->connection = $connection;
      $this->isOpen = true;
    }

    public function isOpen()
    {
      return $this->isOpen;
    }

    public function dispose(bool $disposing)
    {
      if(!$this->disposed){
        if($disposing){
          if($this->isOpen){
            $this->rollback();
          }
        }
        $this->disposed = true;
      }
    }

    public function commit() : void
    {
      //$this->connection->tunnel()->execute('COMMIT');
      foreach($this->connection->repositories as $repository){
        $repository->commit();
      }
      $this->connection->dropTransaction();
      $this->connection = null;
      $this->isOpen = false;
    }

    public function rollback() : void
    {
      if($this->disposed){
        return;
      }

      if(!$this->isOpen){
        throw new \Exception('The transaction isnt closed and usuable');
      }
      $this->connection->dropTransaction();
      $this->connection = null;
    }

    public function rollbackByName() : void
    {

    }

    public function getConnection() : IDbConnection
    {
      return $this->connection;
    }

    public function getIsolationLevel()
    {
      if(!$this->isOpen){
        throw new \Exception('Transaction isnt opened and isnt usuable');
      }
      return $this->isolationLevel;
    }
  }
