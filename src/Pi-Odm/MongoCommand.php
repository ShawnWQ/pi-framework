<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IDbCommand;
use Pi\Odm\MongoQueryBuilder;
use Pi\Odm\MongoTransaction;
use Pi\Odm\MongoConnection;
use Pi\Odm\MongoCommandType;
use Pi\NotImplementedException;

class MongoCommand
  implements IDbCommand {

  public function __construct(protected MongoConnection $connection, protected MongoTransaction $transaction, protected MongoQueryBuilder $builder, protected $timeout = 30)
  {

  }

  public function getCommandType()
  {
    return MongoCommandType::Update;
  }

  public function getTransaction()
  {
    return $this->transaction;
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function cancel()
  {
    throw new NotImplementedException();
  }

  public function dispose()
  {

  }

  public function getCommandTimeout()
  {
    return $this->timeout;
  }
}
