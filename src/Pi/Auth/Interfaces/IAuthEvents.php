<?hh

namespace Pi\Auth\Interfaces;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IService;
use Pi\Interfaces\IResponse;

interface IAuthEvents {

    public function onRegistered(IRequest $httpReq, IAuthSession $session, IService $registrationService) : void;

    public function onAuthenticated(IRequest $httpReq, IAuthSession $session, IService $authService,  $tokens, Map $authInfo)) : void;

    public function onLogout(IRequest $httpReq, IAuthSession $session, IService $authService) : void ;
    
    public function onCreated(IRequest $httpReq, IAuthSession $session) : void ;
}
