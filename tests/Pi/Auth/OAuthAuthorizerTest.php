<?hh

namespace Test\Auth;

use Mocks\BibleHost,
	Mocks\MockAuthProvider,
	Mocks\MockHostConfiguration,
	Pi\InMemoryAppSettingsProvider,
	Pi\Auth\AuthenticateFilter,
	Pi\Auth\AuthUserSession,
	Pi\Auth\AuthTokens,
	Pi\Auth\OAuthSignatureMethodPlainText,
	Pi\Auth\UserAuth,
	Pi\Auth\Authenticate,
	Pi\Auth\OAuthAuthorizer,
	Pi\Auth\CredentialsAuthProvider,
	Pi\Auth\OAuthUtils,
	Pi\Auth\MongoDb\MongoDbAuthRepository;




class OAuthAuthorizerTest extends BaseAuthTest {
	
	const string consumerSecret = 'kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw';
	const string oauthTokenSecret = 'LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE';
	const string oauthConsumerKey = 'xvz1evFS4wEEPTGEFPHBog';
	const string oauthNonce = 'kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg';
	const string oauthSignatureMethod = 'HMAC-SHA1';
	const string oauthTimestamp = '1318622958';
	const string oauthToken = '370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb';
	const string oauthVersion = '1.0';
	const string oauthStatus = 'Hello Ladies + Gentlemen, a signed OAuth request!';
	const string signingKey = 'kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw&LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE';
	const string oauthSignature = 'tnnArxj06cWHq44gCs1OSKk/jLY=';
	const string signatureBaseStr = 'POST&https%3A%2F%2Fapi.twitter.com%2F1%2Fstatuses%2Fupdate.json&include_entities%3Dtrue%26oauth_consumer_key%3Dxvz1evFS4wEEPTGEFPHBog%26oauth_nonce%3DkYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1318622958%26oauth_token%3D370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb%26oauth_version%3D1.0%26status%3DHello%2520Ladies%2520%252B%2520Gentlemen%252C%2520a%2520signed%2520OAuth%2520request%2521';
	const string parameterStr = 'include_entities=true&oauth_consumer_key=xvz1evFS4wEEPTGEFPHBog&oauth_nonce=kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg&oauth_signature_method=HMAC-SHA1&oauth_timestamp=1318622958&oauth_token=370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb&oauth_version=1.0&status=Hello%20Ladies%20%2B%20Gentlemen%2C%20a%20signed%20OAuth%20request%21';
	const string baseUrl = 'https://api.twitter.com/1/statuses/update.json';

	public function testCanMakeNonce()
	{
		$nonce = OAuthAuthorizer::createNonce();
		$this->assertTrue(strlen($nonce) == 16);
	}

	public function testCanCreateTimestamp()
	{
		$timestamp = OAuthAuthorizer::createTimestamp();
		$this->assertTrue($timestamp > 0);
	}

	public function testCanCreateSignature()
	{
		$headers = Map{};
		$headers->add(Pair{'status', self::oauthStatus})
			->add(Pair{'include_entities', 'true'})
			->add(Pair{'oauth_consumer_key', self::oauthConsumerKey})
			->add(Pair{'oauth_nonce', self::oauthNonce})
			->add(Pair{'oauth_signature_method', self::oauthSignatureMethod})
			->add(Pair{'oauth_timestamp', self::oauthTimestamp})
			->add(Pair{'oauth_token', self::oauthToken})
			->add(Pair{'oauth_version', '1.0'});

		$signature = OAuthAuthorizer::createSignature('POST', self::baseUrl, $headers);
		$this->assertEquals(self::signatureBaseStr, $signature);
	}

	public function testCanCreateSigningKey()
	{
		$key = OAuthAuthorizer::createSigningKey(self::consumerSecret, self::oauthTokenSecret);
		$this->assertEquals($key, self::signingKey);
	}

	public function testCanCreateOAuthSignature()
	{
		$oauthSignature = OAuthAuthorizer::createOAuthSignature(self::signingKey, self::signatureBaseStr);
		$this->assertEquals(self::oauthSignature, $oauthSignature);

		$requestUrl = 'http://term.ie/oauth/example/request_token.php';
		$consumerKey = 'key';
		$consumerSecret = 'secret';
		$headers = Map{};
		$headers->add(Pair{'oauth_consumer_key', 'key'})
			->add(Pair{'oauth_nonce', '09d6ba86f6c172581481d50f57776b81'})
			->add(Pair{'oauth_signature_method', 'HMAC-SHA1'})
			->add(Pair{'oauth_timestamp', '1456676895'})
			->add(Pair{'oauth_version', '1.0'});

		$signature = OAuthAuthorizer::createSignature('GET', $requestUrl, $headers);
		$signingKey = OAuthAuthorizer::createSigningKey('secret', null);
		$oauthSignature = OAuthAuthorizer::createOAuthSignature($signingKey, $signature);
		$this->assertEquals('3TMI0VycVyyN0Tx1xRRO4UtYxdI%3D', OAuthUtils::urlencodeRfc3986($oauthSignature));
	}

	public function testHeadersToOAuth()
	{
		$headers = Map{};
		$headers->add(Pair{'testKey', 'testValue'});
		$headers->add(Pair{'testKey1', 'testValue1'});
		$val = OAuthAuthorizer::headersToOAuth($headers);
		$this->assertEquals($val, 'OAuth testKey=testValue,testKey1=testValue1,');
	}

	public function testCanAcquireRequestToken()
	{
		$authorizer = $this->getAuthorizer();
		$authorizer->acquireRequestToken();
	}

	protected function getAuthorizer() : OAuthAuthorizer
	{
		return new OAuthAuthorizer(new MockAuthProvider(new InMemoryAppSettingsProvider()));
	}
}