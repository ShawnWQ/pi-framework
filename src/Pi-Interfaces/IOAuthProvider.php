<?hh

namespace Pi\Interfaces;

interface IOAuthProvider
  extends IAuthProvider{

  public function authHttpGateway();

  public function consumerKey() : string;

  public function consumerSecret(): string;

  public function requestTokenUrl() : string;

  public function authorizeUrl(): string;

  public function accessTokenUrl(): string;
}
