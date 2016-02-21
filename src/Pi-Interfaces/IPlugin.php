<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IPiHost;

interface IPlugin {
  public function register(IPiHost $host) : void;
}
