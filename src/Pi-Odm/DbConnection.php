<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IDbConnection;
use Pi\Odm\IsolationLevel;

abstract class DbConnection
  implements IDbConnection {


    public abstract function commit();

    public abstract function rollback();

    public abstract function getIsolationLevel() : IsolationLevel;

    public function getCommand()
    {
      return $this->getDbCommand();
    }

    protected abstract function getDbCommand();

    public abstract function open();

    public function openAsync()
    {
      $this->open(); // inside try/catch
    }
  }
