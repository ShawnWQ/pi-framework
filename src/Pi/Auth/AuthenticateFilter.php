<?hh

namespace Pi\Auth;

use Pi\SessionPlugin,
    Pi\Filters\RequestFilter,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\Auth\Interfaces\ICryptorProvider,
    Pi\Auth\AuthService,
    Pi\ServiceModel\AuthUserAccount;




class AuthenticateFilter extends RequestFilter {

  public AuthService $authService;

  public function execute(IRequest $req, IResponse $res, $requestDto) : void
  {

    if(!AuthService::hasAuthProviders()) 
      throw new \InvalidArgumentException('The AuthService must be initialized by calling AuthService::init to use an authenticate attribute');

    $requestType = get_class($requestDto);

    // @fix This shouldnt been here but i need this validation to know that the core can handle any registered request type
    $operation = $this->appHost->metadata()->getOperation(get_class($requestDto));
    if($operation === null) { 
      throw new \InvalidArgumentException('Service isnt registered in ServiceMetadata' . get_class($requestDto));}

    $reflMethod = $this->appHost->serviceController()->getReflRequest($requestType);
    
    //if($reflMethod->getAttribute('Auth') == null)
    //  return;

    $token = null;

    $user = null;
    $res = $req->response();
    SessionPlugin::populateSessionFromRequest($req, $res);

    if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = $this->getTokenFromHeaders($req);
        
        if(!is_null($token))
          $user = $this->assertToken(explode(' ', $token)[1]);
      } else if(isset($req->parameters()['access_token'])) {
        $token = $this->getTokenFromParameters($req);
        $user = $this->assertToken($token);
      } else if(isset($_COOKIE['Authorization'])) {
        $user = $this->assertToken($_COOKIE['Authorization']);
      } else {
      // header WWW-Authenticate status 401
//      throw AuthExtensions::throwUnauthorizedRequest();
      $name = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : 'John Doe';
      $user = array('id' => null, 'name' => $name, 'roles' => array());
    }
    if(is_null($user)) {
      return;
    }

    $account = new AuthUserAccount(new \MongoId($user['id']), $user['name'], $user['roles']);
    $req->setUserAccount($account);
  }

  protected function assertToken($token)
  {
    return;
    $user = $this->authService->getUserRedisByToken($token);

    if(!is_array($user)) {
      //throw AuthExtensions::throwUnauthorizedRequest();
      return;
    }

    return $user;

  }

  public function getTokenFromHeaders(IRequest $req)
  {
    return $_SERVER['HTTP_AUTHORIZATION'];
  }

  public function getTokenFromParameters(IRequest $req)
  {
    return $req->parameters()['Authorization'];
  }
}
