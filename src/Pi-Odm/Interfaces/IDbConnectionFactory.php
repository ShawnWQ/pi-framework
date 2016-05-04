<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\Interfaces\IDbConnection;

interface IDbConnectionFactory {
  /**
   * Op DbConnection
   * @return [type] [description]
   */
  public function open() : IDbConnection;
  public function create() : IDbConnection;
}
