<?hh

namespace Pi\Auth;
use Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IHttpResponse,
	Pi\Auth\Interfaces\IAuthSession,
	Pi\Auth\Interfaces\IUserAuth;
use Pi\UnauthorizedException;

/**
 * Helpers extensions for Authentication process like extracting Authorization from Headers
 */
class AuthExtensions {

	public static function getAuthTokenFromBearerRequest(IHttpRequest $request)
	{

	}

	public static function getOAuthTokensFromSession(IAuthSession $session, string $provider)
	{
		foreach ($session->getProviderOAuthAccess() as $tokens) {
			if($tokens->getProvider() == $provider) 
				return $tokens;
		}
		return null;
	}

	public static function populateUserAuthWithSession(IUserAuth &$userAuth, IAuthSession &$session)
	{
		if($userAuth->getDisplayName() != null)
			$session->setDisplayName($userAuth->getDisplayName());
		if($userAuth->getFirstName() != null)
	    	$session->setFirstName($userAuth->getFirstName());
	    if($userAuth->getLastName() != null)
	    	$session->setLastName($userAuth->getLastName());
	    if($userAuth->getEmail() != null)
	    	$session->setEmail($userAuth->getEmail());
	    $session->setCreatedAt(new \DateTime('now'));
	    if($userAuth->getModifiedDate() != null)
	    	$session->setModifiedDate($userAuth->getCreatedDate());
	}

	public static function populateSessionWithUserAuth(IAuthSession &$session, IUserAuth &$userAuth, ?array $authTokens = null)
	{
		$session->setId((string)$userAuth->getId());
		$session->setUserId($userAuth->getId());
		$session->setEmail($userAuth->getEmail());

		if($authTokens != null) {
			    $session->setProviderOAuthAccess($tokens);
		}
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