<?hh


namespace Pi\Auth;

use Pi\HttpRequestHeaders,
	Pi\Common\RandomString,
	Pi\Common\Http\HttpRequest;




class OAuthAuthorizer {

	protected ?string $token;

	protected ?string $verifier;

	protected ?string $xAuthUserName;

	protected ?String $xAuthPassword;

	protected string $requestToken;

	protected string $requestTokenSecret;

	protected string $requestVerifier;

	protected string $authorizationToken;

	protected string $authorizationVerifier;

	protected string $accessToken;

	protected string $accessTokenSecret;
	
	public function __construct(
		protected OAuthProvider $provider
	) {

	}

	/**
	 * Create the timestamp from the current date
	 * @return [type] [description]
	 */
	public static function createTimestamp() : int
	{
		$now = new \DateTime('now');
		return $now->getTimestamp();
	}

	/**
	 * 16 byte lower-case or digit string
	 * @return [type] [description]
	 */
	public static function createNonce() : string
	{
		return RandomString::generate(16);
	}

	public static function createSignature(string $httpMethod, string $baseUri, Map<string,string> $headers)
	{
		// each part is separated by & delimiter
		$parts = array(
			OAuthUtils::getNormalizedHttpMethod($httpMethod),
			OAuthUtils::getNormalizedHttpUrl($baseUri),
			OAuthUtils::getSignableParameters($headers)
		);

		$parts = OAuthUtils::urlencodeRfc3986($parts);

		return implode('&', $parts);
	}

	public static function createSigningKey(string $consumerSecret, ?string $oauthTokenSecret = null) 
	{
		$oauth = ($oauthTokenSecret == null) ? '' : OAuthUtils::urlencodeRfc3986($oauthTokenSecret);
		return OAuthUtils::urlencodeRfc3986($consumerSecret).'&'.$oauth;
	}

	public static function createOAuthSignature(string $signingKey, string $signatureBase)
	{
		$hash = hash_hmac('sha1', $signatureBase, $signingKey, true);
		return base64_encode($hash);
	}
	
	public static function headersToOAuth(Map $headers)
	{	$code = 'OAuth ';
		foreach ($headers as $key => $value) {
			$code .= "$key=$value,";
		}
		return $code;
	}

	public function setAuthorizationToken(string $token)
	{
		$this->token = $token;
	}

	public function setAuthorizationVerifier(string $verifier)
	{
		$this->verifier = $verifier;
	}

	public function setRequestToken(string $token)
	{
		$this->requestToken = $token;
	}

	public function setRequestTokenSecret(string $verifier)
	{
		$this->requestTokenSecret = $verifier;
	}

	public function acquireRequestToken()
	{
		$headers = Map{};
		$headers->add(Pair{'oauth_callback', OAuthUtils::urlencodeRfc3986($this->provider->getCallbackUrl())})
			->add(Pair{'oauth_consumer_key', $this->provider->getConsumerKey()})
			->add(Pair{'oauth_nonce', 'f32b3d6c705fd542cab8b683e2462408'})
			->add(Pair{'oauth_signature_method', 'HMAC-SHA1'})
			->add(Pair{'oauth_timestamp', self::createTimestamp()})
			->add(Pair{'oauth_version', '1.0'});

		$uri = $this->provider->getRequestTokenUrl();
		$signatureHeaders = Map{};
		foreach ($headers as $key => $value) {
			$signatureHeaders[$key] = OAuthUtils::urlencodeRfc3986($value);
		}
		//$signatureHeaders = sort($signatureHeaders->toArray());
		$signature = self::createSignature('GET', $uri, $signatureHeaders);
		$signingKey = self::createSigningKey($this->provider->getConsumerSecret(), null);
		$oauthSignature = self::createOAuthSignature($signingKey, $signature);
		$headers->add(Pair{'oauth_signature', OAuthUtils::urlencodeRfc3986($oauthSignature)});

		$httpReq = new HttpRequest($uri, 'GET');
		$httpReq->addQueryData($headers);
		$httpReq->addHeader(HttpRequestHeaders::Authorization, self::headersToOAuth($headers));

		$msg = $httpReq->send();

	}


	public function acquireAccessToken() 
	{
		$content = '';
		$headers = Map{};
		$headers->add(Pair{"oauth_consumer_key", $this->provider->getConsumerKey()})
			->add(Pair{"oauth_nonce", self::createNonce()})
			->add(Pair{"oauth_signature_method", "HMAC-SHA1"})
			->add(Pair{"oauth_timestamp", self::createTimestamp()})
			->add(Pair{"oauth_version", "1.0"});

		
		if($this->xAuthUserName == null) {
			$headers->add(Pair{"oauth_token", OAuthUtils::urlencodeRfc3986($this->token)})
				->add(Pair{"oauth_verifier", OAuthUtils::urlencodeRfc3986($this->verifier)});
		} else {
			$headers->add(Pair{"x_auth_username", OAuthUtils::urlencodeRfc3986($this->xAuthUserName)})
				->add(Pair{"x_auth_password", OAuthUtils::urlencodeRfc3986($this->xAuthPassword)})
				->add(Pair{"x_auth_mode", "client_auth"});
			$content = sprintf('x_auth_mode=client_auth&x_auth_password=%s&x_auth_username=%s', OAuthUtils::urlencodeRfc3986($this->xAuthPassword), OAuthUtils::urlencodeRfc3986($this->xAuthUserName));
		}
		
		$signature = self::createSignature('POST', $this->provider->getAccessTokenUrl(), $headers);
		$signingKey = self::createSigningKey($this->provider->getConsumerSecret(), $this->requestTokenSecret);
		$oauthSignature = self::createOAuthSignature($signingKey, $signature);
		
		$headers->add(Pair{'oauth_signature', OAuthUtils::urlencodeRfc3986($oauthSignature)});

		// This headers are just used to build signature, dont actually sent to Authorization Server
		if($this->xAuthUserName != null) {
			$headers->remove("x_auth_username")
				->remove("x_auth_password")
				->remove("x_auth_mode");
		}

		$uri = $this->provider->getAccessTokenUrl();
		if(!empty($content)) {
			$uri .= '?' . $content;
		}
		
		$httpReq = new HttpRequest($uri);
		$httpReq->addQueryData($headers);
		$httpReq->addHeader(HttpRequestHeaders::Authorization, self::headersToOAuth($headers));

		$msg = $httpReq->send();
	}
}