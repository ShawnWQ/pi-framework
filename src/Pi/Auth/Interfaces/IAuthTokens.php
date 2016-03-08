<?hh

namespace Pi\Auth\Interfaces;

/*
IUserAuthDetailsExtended
*/
interface IAuthTokens  {

  public function getProvider();

  public function getUserId();

  public function getAccessToken() : string;

  public function getAccessTokenSecret() : string;

  public function getRefreshToken() : string;

  public function getRefreshTokenExpiry() : ?\DateTime;

  public function getRequestToken() : string;

  public function getRequestTokenSecret() : string;

  public function getItems() : array;


}
