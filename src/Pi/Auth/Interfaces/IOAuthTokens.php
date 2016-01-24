<?hh

namespace Pi\Auth;


interface IOAuthTokens {

  public function getProvider() : string;
  public function getUserId() : \MongoId;
  public function getUserName() : string;
  public function getDisplayName() : string;
  public function getEmail() : string;
  public function getBirthDate() : ?DateTime;
  public function getCountry() : string;
  public function getCulture() : string;
  public function getGender() : string;
  public function getTimeZone() : string;
  public function getAccessToken() : string;
  public function getAccessTokenSecret() : string;
  public function getRefreshToken() : string;
  public function getRefreshTokenExpire() : ?DateTime;
  public function getRequestToken() : string;
  public function getRequestTokenSecret() : string;
  public function getItems() : array;
}
