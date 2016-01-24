<?hh

namespace Pi\Odm;

/**
 * Specifies the transaction locking behavior for the connection.
 */
class IsolationLevel {

  const Chaos = 16;
  const ReadUncommitted = 256;
  const ReadCommitted = 4096;
  const RepeatableRead = 65536;
  const Serializable = 1048576;
  const Snapshot = 16777216;
  const Unspecified = -1;
}
