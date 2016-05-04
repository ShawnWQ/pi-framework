<?hh

namespace Pi\Auth;

use Pi\Auth\Interfaces\IAuthMetaProvider;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\FacebookAuthProvider;

class AuthMetaProvider implements IAuthMetaProvider {

  protected $log;

  const ProfileUrlKey = "profileUrl";

  protected $noProfileImgUrl;

  public function setNoProfileImgUrl(string $url) : void
  {
      $this->noProfileImgUrl = $url;
  }
  public function getNoProfileImgUrl() : string
  {
    return $this->noProfileImgUrl;
  }

  public function addMetadata(IAuthTokens $tokens, array $authInfo) : void
  {
    $this->addProfileUrl($tokens, $authInfo);
  }

  public function addProfileUrl(IAuthTokens $tokens, array $authInfo) : void
  {
    if(is_null($tokens) || is_null($authInfo)) {
      return;
    }

    $items = $tokens->getItems() ? : array();
    if(array_key_exists($items, $this->profileUrlKey)) {
      return;
    }

    try {

      /*
       * @todo ao check from the email in gravatar
       */
      switch($tokens->getProvider()) {
        case FacebookAuthProvider::name:
          $items[$this->profileUrlKey] = self::getRedirectUrlIfAny(sprintf('http://avatars.io/facebook/{0}?size=medium', $tokens->getUserName()));
        break;
      }

    }
    catch(\Exception $ex) {

    }
  }

  public static function getRedirectUrlIfAny(string $url) : ?string
  {
    // Read the Location header
    // Read the location query parameter
    // From current host context. catch fails and keep going

    return $url;
  }


  public function getProfileUrl(IAuthSession $authSession, ?string $defaultUrl = null) : string
  {

  }

}
