<?hh
use Mocks\BibleHost;
use Pi\Auth\AuthenticateFilter;
use Pi\Service;
use Pi\Interfaces\IContainer;
use Pi\UnauthorizedException;
use Pi\ServiceModel\AuthUserAccount;

class ATRequest {

}
class ATService extends Service {

	<<Request,Auth,Route('/test'),Method('POST')>>
	public function postProtected(ATRequest $request){  }
}

class ATHost extends BibleHost {
  	public function configure(IContainer $container) : void
  	{
      parent::configure($container);
		  $this->registerService(new ATService());
  	}
}
class AuthenticateFilterTest extends \PHPUnit_Framework_TestCase {

  protected $host;

  protected $repo;

  protected $service;

  public function setUp()
  {
    $this->host = new ATHost();
    $this->repo = $this->host->tryResolve('Pi\Auth\Auth');
    $this->service = $this->host->container()->getService(new \Pi\Auth\AuthService());
  }
  
  public function testFilterCanReadAuthentication()
  {
    $_SERVER['REQUEST_URI'] = '/test';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $id = new \MongoId();
    $token = $this->service->createAuthToken($id, 'login');
    $_REQUEST['Authorization'] = $token->getCode();

    
    $this->host->init();
    $req = $this->host->tryResolve('IRequest');
    $user = $req->userAccount();
    $this->assertTrue($user instanceof AuthUserAccount);
    
  }
  
  public function testFilterThrowUnauthorizedExceptionsIfNoAuthProvided()
  {
  	$_SERVER['REQUEST_URI'] = '/test';
    $_SERVER['REQUEST_METHOD'] = 'POST';

   
    $throwed = false;

  	try {
      $this->host->init();
    }
    catch(UnauthorizedException $ex) {
      $throwed = true;
    }
    
    $this->assertTrue($throwed);
  }  
}
