<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\Interfaces\IDataRecord;

interface IDataReader
  extends IDataRecord{

  /**
   * Gets a value indicating whether the data reader is closed.
   */
  public function isClosed();

  /**
   * Gets the number of documents changed, inserted, or deleted by execution of the DB statement.
   */
  public function recordsAffected();

  /**
   * Closes the IDataReader Object.
   * @return void
   */
  public function close();

  /**
   * Advances the data reader to the next result, when reading the results of batch SQL statements.
   */
  public function nextResult();

  /**
   * Advances the IDataReader to the next record.
   * @return [type] [description]
   */
  public function read();

}
