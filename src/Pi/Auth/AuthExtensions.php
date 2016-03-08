<?hh

namespace Pi\Auth;
use Pi\Interfaces\IRequest,
	Pi\Auth\Interfaces\IAuthSession,
	Pi\Auth\Interfaces\IUserAuth;
use Pi\UnauthorizedException;

/**
 * Helpers extensions for Authentication process like extracting Authorization from Headers
 */
class AuthExtensions {

	public static function getAuthTokenFromBearerRequest(IRequest $request)
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
		if($session->getDisplayName() != null)
			$userAuth->setDisplayName($session->getDisplayName());
		if($session->getFirstName() != null)
	    	$userAuth->setFirstName($session->getFirstName());
	    if($session->getLastName() != null)
	    	$userAuth->setLastName($session->getLastName());
	    if($session->getEmail() != null || $session->getPrimaryEmail() != null)
	    	$userAuth->setEmail($session->getEmail() ?: $session->getPrimaryEmail());
	    $session->setCreatedAt(new \DateTime('now'));
	    if($session->getFacebookUserId() != null)
	    	$userAuth->setFacebookUserId($session->getFacebookUserId());
	    //if($session->getModifiedDate() != null)
	    //	$userAuth->setModifiedDate($userAuth->getCreatedDate());
	}

	public static function populateSessionWithUserAuth(IAuthSession &$session, IUserAuth &$userAuth, ?array $authTokens = null)
	{
		$session->setId((string)$userAuth->getId());
		$session->setUserId($userAuth->getId());
		$session->setEmail($userAuth->getEmail());
		if($authTokens != null) {
			$session->setProviderOAuthAccess($tokens);
		}
		if($userAuth->getFacebookUserId() != null) {
			$session->setFacebookUserId($userAuth->getFacebookUserId());
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