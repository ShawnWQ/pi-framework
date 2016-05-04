<?hh

namespace Test\Auth;

use Mocks\BibleHost;
use Pi\Auth\AuthenticateFilter,
	Pi\Auth\AuthUserSession,
	Pi\Auth\AuthTokens,
	Pi\Auth\UserAuth,
	Pi\Auth\CredentialsAuthProvider,
	Pi\Auth\MongoDb\MongoDbAuthRepository;


abstract class BaseAuthTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected function getAuthUserRepository()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\IAuthUserRepository');
	}

	protected function getAuthRepository()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\IAuthRepository');
	}

	protected function getAuthDetailsRepository()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\IAuthDetailsRepository');
	}

	public function getCryptor()
	{
		return $this->host->container()->get('Pi\Auth\Interfaces\ICryptorProvider');
	}

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
	}

	protected function createAuthTokens()
	{
		$tokens = new AuthTokens();
		$tokens->setFirstName('Guilherme');
		$tokens->setLastName('Cardoso');
		$tokens->setDisplayName('Guilherme Cardoso');
		$tokens->setEmail('email@ŋuilhermecardoso.pt');
		$tokens->setProvider(CredentialsAuthProvider::name);
		return $tokens;
	}

	protected function createAuthUserSession()
	{
		$session = new AuthUserSession();
		$session->setFirstName('Guilherme');
		$session->setLastName('Cardoso');
		$session->setDisplayName('Guilherme Cardoso');
		$session->setEmail('email@ŋuilhermecardoso.pt');
		return $session;
	}

	protected function createUserAuth() : UserAuth
	{
		$user = new UserAuth();
		$user->setFirstName('Guilherme');
		$user->setLastName('Cardoso');
		$user->setEmail('email@guilhermecardoso.pt');
		$user->setDisplayName('Guilherme Cardoso');
		return $user;
	}
}