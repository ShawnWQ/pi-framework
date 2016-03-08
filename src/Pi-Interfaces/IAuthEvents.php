<?hh

namespace Pi\Interfaces;

interface IAuthEvents {

  public function onRegistered(IRequest $httpReq, IAuthSession $session, IServiceBase $registrationService): void;
  /**
   * @param authInfo Dictionary<string, string>
   */
  public function onAuthenticated(IRequest $httpReq, IAuthSession $session, IServiceBase $authService, IAuthTokens $tokens, $authInfo): void;

  public function onLogout(IRequest $httpReq, IAuthSession $session, IServiceBase $authService): void;
  public function OnCreated(IRequest $httpReq, IAuthSession $session): void;
}
