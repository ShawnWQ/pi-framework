<?hh

namespace Pi\Auth\Interfaces;

interface IOAuthProvider extends IAuthProvider {

  public function getConsumerKey() : string;

  public function getConsumerSecret() : string;

  public function getRequestTokenUrl() : string;

  public function getAuthorizeUrl() : string;

  public function getAccessTokenUrl() : string;
}
