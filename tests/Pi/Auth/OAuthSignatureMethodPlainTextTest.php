<?hh

namespace Test\Auth;

use Mocks\BibleHost;
use Pi\Auth\AuthenticateFilter,
	Pi\Auth\AuthUserSession,
	Pi\Auth\AuthTokens,
	Pi\Auth\OAuthSignatureMethodPlainText,
	Pi\Auth\UserAuth,
	Pi\Auth\Authenticate,
	Pi\Auth\CredentialsAuthProvider,
	Pi\Auth\MongoDb\MongoDbAuthRepository;

class OAuthSignatureMethodPlainTextTest extends BaseAuthTest {
	
	public function testCanBuildAndCheckSignature()
	{
		$method = new OAuthSignatureMethodPlainText();
		$req = new Authenticate();
		$req->setUserName('email@guilhermecardoso.pt');
		$req->setPassword('123');
		$signature = $method->buildSignature($req, 'consumer-mock', 'token-mock');
		$this->assertTrue($method->checkSignature($req, 'consumer-mock', 'token-mock', $signature));
	}	
}