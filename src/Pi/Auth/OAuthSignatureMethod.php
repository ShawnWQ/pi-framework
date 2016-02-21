<?hh

namespace Pi\Auth;


/**
 * Class for implementing a Signature Method
 */
abstract class OAuthSignatureMethod {

	/**
	 * Name of the Signature Method (ie HMAC-SHA1)
	 */
	abstract public function getName() : string;

	/**
	 * Build up the signature
	 */
	public function buildSignature(Authenticate $request, $consumerSecret, $tokenSecret) : string;

	/**
	 * Verifies that a given signature is correct
	 */
	public function checkSignature(Authenticate $request, string $consumerSecret, string $tokenSecret, string $signature) {
		$built = $this->buildSignature($request, $consumerSecret, $tokenSecret);
		return $built == $signature;
	}
}