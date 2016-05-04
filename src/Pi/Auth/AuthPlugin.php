<?hh

namespace Pi\Auth;

use Facebook\Facebook;
use Pi\Interfaces\IPostInitPlugin;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\PIPiHost;
use Pi\EventManager;
use Pi\Auth\MongoDb\MongoDbAuthRepository;
use Pi\Auth\MongoDbAuthUserRepository,
	Pi\Auth\MongoDb\MongoDbAuthDetailsRepository,
	Pi\Auth\Validators\BasicRegistrationValidator,
	Pi\Auth\Interfaces\IAuthRepository,
	Pi\Auth\Interfaces\IAuthDetailsRepository,
	Pi\Auth\Interfaces\IAuthUserRepository;




class AuthPlugin implements IPostInitPlugin, IPlugin {

	protected $session;

	protected Set<IAuthEvents> $authEvents;

	public function __construct(protected ?AuthConfig $config = null) {
		if($this->config === null) {
			$this->config = new AuthConfig();
		}
		$this->authEvents = Set{};
	}

	public function register(IPiHost $appHost) : void
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

		$authSvc = new AuthService();
		$provider = $appHost->container->get('Pi\Auth\Interfaces\ICryptorProvider');
		$this->session = new AuthUserSession();
		$authSvc->init(array(
				new CredentialsAuthProvider($appHost->appSettings(), '/realm', CredentialsAuthProvider::name, $provider),
				new FacebookAuthProvider($appHost->appSettings())
			),
			$this->session);
		
		$appHost->registerServiceInstance($authSvc);
		$appHost->registerService(RegisterService::class);
		$appHost->registerService(RegistrationAvailabilityService::class);
		$appHost->addRequestFiltersClasses(new AuthenticateFilter());
		$repo = new MongoDbAuthRepository();
		$detailsRepo = new MongoDbAuthDetailsRepository();

		$appHost->container()->register('Pi\Auth\AuthConfig', function(IContainer $container) use($config){
			return $config;
		});

		$appHost->container()->registerAutoWiredAs(MongoDbAuthRepository::class, IAuthRepository::class);
		$appHost->container()->registerAutoWiredAs(MongoDbAuthRepository::class, IAuthUserRepository::class);
		$appHost->container()->registerAutoWiredAs(MongoDbAuthDetailsRepository::class, IAuthDetailsRepository::class);
		$appHost->container()->registerRepository(UserAuthDetails::class, IAuthRepository::class);
		$appHost->container()->registerRepository(UserEntity::class, IAuthRepository::class);
		$appHost->container()->registerRepository(UserAuth::class, IAuthDetailsRepository::class);
		//$appHost->registerValidator('Pi\ServiceModel\BasicRegisterRequest', BasicRegistrationValidator::instance());

		//$appHost->container()->registerRepository(new UserEntity(), new UserRepository());
	}

	public function afterPluginsLoaded(IPiHost $appHost) {
		$events = $appHost->tryResolve('IAuthEvents');
		if($events == null) {
			$events = $this->authEvents->count() == 0
				? new AuthEvents() :
					$this->authEvents->count() == 1 
					? $this->authEvents->firstValue()
					: new AuthEvents();
		}
	}
}
