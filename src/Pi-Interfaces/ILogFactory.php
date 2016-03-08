<?hh

namespace Pi\Interfaces;

interface ILogFactory {
  public function getLogger($type);
}
