<?hh

namespace Pi\Auth;

/**
 * The Plain Text method does not provide any security protection
 * Should only be used over a secure channel such as HTTPS
 */
 
class OAuthSignatureMethodPlainText extends OAuthSignatureMethod {
  
  public function getName() : string
  {
    return "PLAINTEXT";
  }
  /**
   * oauth_signature is set to the concatenated encoded values of the Consumer Secret and
   * Token Secret, separated by a '&' character (ASCII code 38), even if either secret is
   * empty. The result MUST be encoded again.
   *   - Chapter 9.4.1 ("Generating Signatures")
   *
   * Please note that the second encoding MUST NOT happen in the SignatureMethod, as
   * OAuthRequest handles this!
   */
  public function buildSignature(Authenticate $request, $consumerSecret, $tokenSecret) : string
  {
    $key_parts = array(
      $consumerSecret ?: '',
      $tokenSecret ?: ''
    );
    $key_parts = OAuthUtils::urlencodeRfc3986($key_parts);
    $key = implode('&', $key_parts);
    return $key;
  }
}