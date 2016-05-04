<?hh

namespace Test\Auth\MongoDb;

use Mocks\BibleHost,
	Pi\Auth\AuthenticateFilter,
	Pi\Auth\AuthUserSession,
	Pi\Auth\AuthTokens,
	Pi\Auth\CredentialsAuthProvider,
	Pi\Auth\MongoDb\MongoDbAuthRepository,
	Test\Auth\BaseAuthTest;


class MongoDbAuthRepositoryTest extends BaseAuthTest {

	protected $host;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->host->init();
	}

	public function testFilterIsRegisteredAtCore()
	{
		
		$this->assertTrue($this->host->container()->get('Pi\Auth\Interfaces\IAuthRepository') != null);
		$this->assertTrue($this->host->container()->get('Pi\Auth\Interfaces\IAuthUserRepository') != null);
	}

	public function testCanCreateNewAuthSession()
	{
		$authRepo = $this->getAuthRepository();
		$detailsRepo = $this->getAuthDetailsRepository();
		$session = $this->createAuthUserSession();
		$tokens = $this->createAuthTokens();

		$auth = $authRepo->getUserAuth($session, $tokens);
		$this->assertTrue($auth == null);
		$res = $authRepo->createOrMergeAuthSession($session, $tokens);
		$session->setUserId($res->getUserId());

		$auth = $authRepo->getUserAuth($session, $tokens);
		$this->assertTrue($auth != null);
	}
}
