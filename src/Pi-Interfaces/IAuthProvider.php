<?hh

namespace Pi\Interfaces;

interface IAuthProvider {

	public function authRealm();

	public function provider();

	public function callbackUrl();

	public function logOut(IServiceBase $service, Authenticate $request);

	public function authenticate(IServiceBase $service, IAuthSession $session, Authenticate $request);

	public function isAuthorized(IAuthSession $session, IAuthTokens $tokens, Authenticate $request); // implement request to be nullable
}
