<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\IRequest;
use Pi\ApplyTo;

class RequestExtensions {

  public static function httpMethodAsApplyTo(IRequest $request)
  {
      $verbe = $request->verbe();
      // check if exists
      return ApplyTo::Get;
  }

}
