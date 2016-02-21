<?hh // strict

namespace Pi\Auth;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IService;
use Pi\Interfaces\IResponse;
use Pi\Auth\Interfaces\IAuthEvents;

class AuthEvents {

	protected $authEvents;

	public function __construct(protected ?IAuthEvents $events = null) {
		$this->authEvents = $events;
	}

	public function onRegistered(IRequest $httpReq, IAuthSession $session, IService $registrationService) : void {
		foreach($this->authEvents as $k => $fn) {
	      $fn->onRegistered($rhttpReq, $session, $registrationService);
	    }
	}

    public function onAuthenticated(IRequest $httpReq, IAuthSession $session, IService $authService, $tokens, Map $authInfo) : void {
		foreach($this->authEvents as $k => $fn) {
	      $fn->onAuthenticated($rhttpReq, $session, $authService, $tokens, $authInfo);
	    }
	}

    public function onLogout(IRequest $httpReq, IAuthSession $session, IService $authService) : void {
		foreach($this->authEvents as $k => $fn) {
	      $fn->onLogout($rhttpReq, $session, $authService);
	    }
    }

    public function onCreated(IRequest $httpReq, IAuthSession $session) : void {
		foreach($this->authEvents as $k => $fn) {
	      $fn->onCreated($rhttpReq, $session);
	    }
    }

}
