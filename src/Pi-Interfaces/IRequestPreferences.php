<?hh

namespace Pi\Interfaces;

interface IRequestPreferences{

  public function acceptsGzip() : bool;

  public function acceptsDeflate() : bool;
}
