<?hh

namespace Pi\Auth;

use Facebook\Facebook;
use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\PIPiHost;
use Pi\EventManager;
use Pi\Auth\MongoDb\MongoDbAuthRepository;
use Pi\Auth\MongoDbAuthUserRepository;

class AuthPlugin implements IPreInitPlugin, IPlugin {

	public function __construct(protected ?AuthConfig $config = null)
	{
		if($this->config === null) {
			$this->config = new AuthConfig();
		}
	}

	public function configure(IPiHost $appHost) : void
	{

		$config = $this->config;
		if(is_array($appHost->config()->oAuths())) {
			foreach($appHost->config()->oAuths() as $key => $auth) {
				if($key === 'facebook') {
				
				}
			}
		}

		$s = new AuthService();
		$s->init(array
				(new CredentialsAuthProvider($appHost->config(), '/realm', 'basic')),
				new AuthUserSession());
		
		$appHost->registerService($s);

		$appHost->registerService(new RegisterService());

		$appHost->registerService(new RegistrationAvailabilityService());

		$appHost->addRequestFiltersClasses(new AuthenticateFilter());

		$appHost->container()->register('Pi\Auth\AuthConfig', function(IContainer $container) use($config){
			return $config;
		});


		$appHost->container()->registerRepository(new UserEntity(), new MongoDbAuthUserRepository());
		$appHost->container()->registerRepository(new Auth(), new MongoDbAuthRepository());

		//$appHost->container()->registerRepository(new UserEntity(), new UserRepository());
	}
}
