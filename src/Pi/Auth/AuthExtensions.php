<?hh

namespace Pi\Auth;
use Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IHttpResponse;
use Pi\UnauthorizedException;

/**
 * Helpers extensions for Authentication process like extracting Authorization from Headers
 */
class AuthExtensions {

	public static function getAuthTokenFromBearerRequest(IHttpRequest $request)
	{

	}

	public static function extractAuthFromParams()
	{

	}

	/**
	 * Throw a new exception for the current request
	 */
	public static function throwUnauthorizedRequest()
	{
		$ex = new UnauthorizedException();
		throw $ex;
	}
}