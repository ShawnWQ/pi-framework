<?hh

namespace Pi\Auth;

use Pi\Auth\Interfaces\IUserAuthDetails;

<<Entity,Collection("auths")>>
class UserAuthDetails implements IUserAuthDetails {

  protected \MongoId $id;

  protected \MongoId $userId;

  protected \DateTime $createdDate;

  protected \DateTime $modifiedDate;

  protected string $provider;

  protected string $accessToken;

  protected string $accessTokenSecret;

  protected string $refreshToken;

  protected ?\DateTime $refreshTokenExpiry;

  protected string $requestToken;

  protected string $requestTokenSecret;

  protected array $items;

  protected \MongoId $refId;

  protected string $userName;

  protected string $displayName;

  protected string $firstName;

  protected string $lastName;

  protected array $meta;

  <<Collection>>
  public function getMeta()
  {
    return $this->meta;
  }
  
  public function setMeta(array $values)
  {
    $this->meta = $values;
  }

  <<String>>
  public function getDisplayName() : string
  {
    return $this->displayName;
  }

  public function setDisplayName(string $userName)
  {
    $this->displayName = $userName;
  }

  <<String>>
  public function getFirstName() : string
  {
    return $this->firstName;
  }

  public function setFirstName(string $userName)
  {
    $this->firstName = $userName;
  }

  <<String>>
  public function getLastName() : string
  {
    return $this->lastName;
  }

  public function setLastName(string $userName)
  {
    $this->lastName = $userName;
  }

  <<String>>
  public function getUserName() : string
  {
    return $this->userName;
  }

  public function setUserName(string $userName)
  {
    $this->userName = $userName;
  }

  <<String>>
  public function getRefId()
  {
    return $this->refId;
  }

  public function setRefId($id)
  {
    $this->refId = $id;
  }

  <<String>>
  public function getRefIdStr()
  {
    return (string)$this->refId;
  }



  <<Id>>
  public function getId()
  {
    return $this->id;
  }

  <<ObjectId>>
  public function getUserId()
  {
    return $this->userId;
  }

  public function setUserId(\MongoId $value)
  {
    $this->userId = $value;
  }

  <<DateTime>>
  public function getCreatedDate() : \DateTime
  {
    return $this->createdDate;
  }

  public function getModifiedDate() : \DateTime
  {
    return $this->modifiedDate;
  }

  <<String>>
  public function getProvider()
  {
    return $this->provider;
  }

  public function setProvider(string $value)
  {
    $this->provider = $value;
  }

  <<String>>
  public function getAccessToken() : string
  {
    return $this->accessToken;
  }

  public function setAccessToken(string $value)
  {
    $this->accessToken = $value;
  }

  <<String>>
  public function getAccessTokenSecret() : string
  {
    return $this->accessTokenSecret;
  }

  public function setAccessTokenSecret(string $value)
  {
    $this->accessTokenSecret = $value;
  }

  public function getRefreshToken() : string
  {
    return $this->refreshToken;
  }

  public function setRefreshToken(string $value)
  {
    $this->refreshToken = $value;
  }

  public function getRefreshTokenExpiry() : ?\DateTime
  {
    return $this->refreshTokenExpiry;

  }

  <<String>>
  public function getRequestToken() : string
  {
    return $this->requestToken;
  }

  public function setRequestToken(string $value) : void
  {
    $this->requestToken = $value;
  }

  public function getRequestTokenSecret() : string
  {
    return $this->requestTokenSecret;
  }

  <<Collection>>
  public function getItems() : array
  {
    return $this->items;
  }
}
