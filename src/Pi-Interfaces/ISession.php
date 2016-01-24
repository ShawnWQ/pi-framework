<?hh

namespace Pi\Interfaces;

interface ISession {

  public function get(string $key);

  public function set(string $key, string $value);
}
