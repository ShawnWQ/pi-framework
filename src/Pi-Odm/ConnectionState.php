<?hh

namespace Pi\Odm;

class ConnectionState {
  const Open = 1;
  /**
   * The connection is closed.
   */
  const Closed = 2;
  /**
   * The connection object is connecting to the data source.
   */
  const Connecting = 3;
  /**
   * The connection object is executing a command.
   */
  
  const Executing = 4;
  /**
   * The connection object is retrieving data.
   */
  const Fetching = 5;

  /**
   * The connection to the data source is broken. This can occur only after the connection has been opened. A connection in this state may be closed and then re-opened.
   */
  const Broken = 7;
}
