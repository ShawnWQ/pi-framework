<?hh

namespace Pi\Interfaces;

interface IRoutesManager {

  public function add($restPath, $serviceType, $requestType, array $verbs, $summary = null, $notes = null);

  public function get($restPath);

  public function routes();
}
