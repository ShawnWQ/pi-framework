<?hh

namespace Pi\Auth;
use Pi\Filters\RequestFilter;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IHttpRequest;
use Pi\Auth\AuthService;
use Pi\ServiceModel\AuthUserAccount;

class AuthenticateFilter extends RequestFilter {

  public AuthService $authService;

  public function execute(IRequest $req, IResponse $res, $requestDto) : void
  {

    $requestType = get_class($requestDto);
    $operation = $this->appHost->metadata()->getOperation(get_class($requestDto));

    if($operation === null) { throw new \Exception('Service isnt registered in ServiceMetadata' . get_class($requestDto));}

    $reflMethod = $this->appHost->serviceController()->getReflRequest($requestType);

    $attr = $reflMethod->getAttribute('Auth');
    $required = true;
    if($attr === null) {
      $required = false;
    }

    $token = null;

    $user = null;

    if(!$req instanceof IHttpRequest) {
      throw new \Exception('Non supported');
    } else if(isset($_SERVER['HTTP_AUTHORIZATION'])) {

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
    $user = $this->authService->getUserRedisByToken($token);

    if(!is_array($user)) {
      //throw AuthExtensions::throwUnauthorizedRequest();
      return;
    }

    return $user;

  }

  public function getTokenFromHeaders(IHttpRequest $req)
  {
    return $_SERVER['HTTP_AUTHORIZATION'];
  }

  public function getTokenFromParameters(IHttpRequest $req)
  {
    return $req->parameters()['Authorization'];
  }
}
