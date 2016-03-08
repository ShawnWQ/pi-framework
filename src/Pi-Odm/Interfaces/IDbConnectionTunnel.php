<?hh

namespace Pi\Odm\Interfaces;

interface IDbConnectionTunnel {

  public function execute($command, $transactionName = null);
  public function isConnected() : bool;
}
