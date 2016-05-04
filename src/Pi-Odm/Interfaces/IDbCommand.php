<?hh

namespace Pi\Odm\Interfaces;

interface IDbCommand {

  /**
   * Gets the wait time before terminating the attempt to execute a command and generating an error.
   */
  public function getCommandTimeout();

  /**
   * Indicates or specifies how the CommandText property is interpreted.
   */
  public function getCommandType();

  /**
   * Gets the IDbConnection used by this instance of the IDbCommand.
   *  @return IDbConnection
   */
  public function getConnection();


  /**
   * Gets the transaction within which the Command object of a .NET Framework data provider executes.
   */
  public function getTransaction();

  /**
   * Attempts to cancels the execution of an IDbCommand.
   */
  public function cancel();

  /**
   * Performs application-defined tasks associated with freeing, releasing, or resetting unmanaged resources. (Inherited from IDisposable.)
   */
  public function dispose();
}
