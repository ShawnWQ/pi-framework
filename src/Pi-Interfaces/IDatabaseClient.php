<?hh

namespace Pi\Interfaces;
use Pi\Odm\MongoUpdateQueryBuilder;

interface IDatabaseClient {

  public function update(MongoUpdateQueryBuilder $update);
}
