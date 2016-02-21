<?hh


namespace Pi\Auth;


class OAuthAuthorizer {
	protected ?string $token;

	protected ?string $verifier;
	
	public function __construct(
		protected OAuthProvider $provider
	) {

	}

	protected static function getSignatureBase() : string {
		// each part is separated by & delimiter
		$parts = array(
			OAuthUtils::getNormalizedHttpMethod($httpMethod),
			OAuthUtils::getNormalizedHttpUrl($url),
			OAuthUtils::getSignableParameters($parms)
		);

		$parts = OAuthUtils::urlencodeRfc3986($parts);

		return implode('&', $parts);
	}

	public function setAuthorizationToken(string $token) {
		$this->token = $token;
	}

	public function setAuthorizationVerifier(string $verifier) {
		$this->verifier = $verifier;
	}

	/**
	 * Invoked after the user has authorized
	 */
	public function acquireAccessToken() {
		$headers = Map{
			Pair{"oauth_consumer_key", $provider->getConsumerKey()},
			Pair{"oauth_nonce", $this->createNonce()},
			Pair{"oauth_signature_method", "HMAC-SHA1"},
			Pair{"oauth_timestamp", $this->createTimestamp()},
			Pair{"oauth_version", "1.0"}
		};
		if($this->token !== null)
			$headers->add(Pair{"oauth_token", $this->token});

		if($this->verifier !== null) 
			$headers->add(Pair{"oauth_vetifier", $this->verifier});
	}

	protected function createTimestamp() : int {
		return 0;
	}

	protected function createNonce() : string {
		return 'nnce';
	}

	protected function createSignature(string $httpMethod, string $baseUri, Map<string,string> $headers) {

	}

	protected function createSigningKey(string $consumerSecret, string $oauthTokenSecret) {

	}

	protected function createOAuthSignature(string $compositeSigningKey, string $signatureBase)
	{

	}
}