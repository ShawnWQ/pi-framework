<?hh

namespace Pi\Odm;

use Pi\Odm\Interfaces\IDataAdapter;
use Pi\Odm\Interfaces\IDbCommand;
use Pi\Odm\DataAdapter;
use Pi\Odm\MongoCommand;

class MongoAdapter
  extends DataAdapter
  implements IDataAdapter {

  protected $connection;


  public function __construct(protected MongoCommand $selectCommand)
  {
      $this->connection = getConnection();
  }

  public function deleteCommand() : IDbCommand
  {
    throw new \Pi\NotImplementedException();
  }

  public function selectCommand() : IDbCommand
  {
    throw new \Pi\NotImplementedException();
  }

  public function insertCommand() : IDbCommand
  {
    throw new \Pi\NotImplementedException();
  }

  public function updateCommand() : IDbCommand
  {
    throw new \Pi\NotImplementedException();
  }
}
