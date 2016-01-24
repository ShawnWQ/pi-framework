<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IPiHost;

interface IPlugin {
  public function configure(IPiHost $host) : void;
}
