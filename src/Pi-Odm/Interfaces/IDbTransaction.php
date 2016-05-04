<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\Interfaces\IDbConnection;

interface IDbTransaction {

  public function commit() : void;

  public function rollback() : void;

  public function getConnection() : IDbConnection;

  public function getIsolationLevel();
}
