<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\MongoEntity;

interface IDbConnection {


  public function getConnectionString();
  public function getConnectionTimeout();
  public function getDatabase();
  public function getState();

  public function beginTransaction();
  public function beginIsolatedTransaction($level);

  /**
   * Changes the current database for an open Connection object
   */
  public function changeDatabase();

  public function close();

  public function insert<T>(T $entity);

  public function select($query);

  public function save($entity);

  public function update($entity);

  public function deletebyId($id);

  /**
   * Create and return an command associated with the connection
   */
  public function createCommand();

  /**
   * Performs application-defined tasks associated with freeing, releasing, or resetting unmanaged resources. (Inherited from IDisposable.)
   * @return [type] [description]
   */
  public function dispose();
}
