<?hh

namespace Pi\Odm;
use Pi\Odm\MongoDatabase;
use Pi\Interfaces\IDatabaseClient;
use Pi\Odm\MongoUpdateQueryBuilder;

class MongoClient
  implements IDatabaseClient {

  public function __construct(protected MongoDatabase $database)
  {

  }

  public function update(MongoUpdateQueryBuilder $update)
  {

  }

  public function commit()
  {
    
  }
}
