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
use Pi\Auth\MongoDbAuthUserRepository,
	Pi\Auth\MongoDb\MongoDbAuthDetailsRepository;

class AuthPlugin implements IPreInitPlugin, IPlugin {

	protected $session;

	public function __construct(protected ?AuthConfig $config = null)
	{
		if($this->config === null) {
			$this->config = new AuthConfig();
		}
	}

	public function configure(IPiHost $appHost) : void
	{
		
		$appHost->container->register('Pi\Auth\Interfaces\ICryptorProvider', function(IContainer $container) {
			return new Md5CryptorProvider();
		});

		$config = $this->config;
		if(is_array($appHost->config()->oAuths())) {
			foreach($appHost->config()->oAuths() as $key => $auth) {
				if($key === 'facebook') {
				
				}
			}
		}

		$s = new AuthService();
		$provider = $appHost->container->get('Pi\Auth\Interfaces\ICryptorProvider');
		$this->session = new AuthUserSession();
		$s->init(array
				(new CredentialsAuthProvider($appHost->appSettings(), '/realm', CredentialsAuthProvider::name, $provider)),
				$this->session);
		
		$appHost->registerService($s);

		$appHost->registerService(new RegisterService());

		$appHost->registerService(new RegistrationAvailabilityService());

		$appHost->addRequestFiltersClasses(new AuthenticateFilter());

		$repo = new MongoDbAuthRepository();
		$detailsRepo = new MongoDbAuthDetailsRepository();

		$appHost->container()->register('Pi\Auth\AuthConfig', function(IContainer $container) use($config){
			return $config;
		});

		$appHost->container()->register('Pi\Auth\Interfaces\IAuthRepository', function(IContainer $container) use($repo){
			return $container->get('Pi\Auth\MongoDb\MongoDbAuthRepository');
		});

		$appHost->container()->register('Pi\Auth\Interfaces\IAuthUserRepository', function(IContainer $container) use($repo){
			return $container->get('Pi\Auth\MongoDb\MongoDbAuthRepository');
		});

		$appHost->container()->register('Pi\Auth\Interfaces\IAuthDetailsRepository', function(IContainer $container) use($detailsRepo){
			return $container->get('Pi\Auth\MongoDb\MongoDbAuthDetailsRepository');
		});

		$appHost->container()->registerRepository(new UserAuthDetails(), $detailsRepo);
		$appHost->container()->registerRepository(new UserEntity(), $repo);
		$appHost->container()->registerRepository(new UserAuth(), $repo);

		//$appHost->container()->registerRepository(new UserEntity(), new UserRepository());
	}
}
