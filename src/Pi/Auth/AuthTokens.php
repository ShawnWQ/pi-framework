<?hh

namespace Pi\Auth;

use Pi\Auth\Interfaces\IAuthTokens;

class AuthTokens implements IAuthTokens, \JsonSerializable {

	public function jsonSerialize()
	{
		$vars = get_object_vars($this);
		$vars['userId'] = (string)$vars['userId'];
		return $vars;
	}

  protected string $provider;

  protected $userId;

  protected $firstName;

  protected $lastName;

  protected $email;

  protected $password;

  protected $displayName;

  protected $state;

  protected string $userName = '';

  protected string $company = '';

  protected string $phoneNumber = '';

  protected \DateTime $birthDate;

  protected string $address = '';

  protected string $address2 = '';

  protected string $city = '';

  protected string $country = '';

  protected string $culture = '';

  protected string $fullName = '';

  protected string $gender = '';

  protected string $language = '';

  protected string $mailAddress = '';

  protected string $nickName = '';

  protected string $postalCode = '';

  protected string $timeZone = '';

  protected array $items;

  protected string $accessToken = '';

  protected string $accessTokenSecret = '';

  protected string $refreshToken = '';

  protected ?\Datetime $refreshTokenExpire;

  protected string $requestToken = '';

  protected string $requestTokenSecret = '';

  protected $userAuthId;



  <<Collection>>
  public function getItems() : array
  {
    return $this->items;
  }

  public function setItems(array $value) : void
  {
    $this->items = $value;
  }

  public function addItem(string $key, string $value) : void
  {
  	$this->items[$key] = $value;
  }

  <<String>>
	public function getUserName() : string
	{
		return $this->userName;
	}

	public function setUserName(string $value) : void
	{
		$this->userName = $value;
	}

	<<String>>
  public function getDisplayName() : string
	{
		return $this->displayName;
	}

	public function setDisplayName(string $value) : void
	{
		$this->displayName = $value;
	}

	<<String>>
  public function getFirstName() : string
	{
		return $this->firstName;
	}

	public function setFirstName(string $value) : void
	{
		$this->firstName = $value;
	}

	<<String>>
  public function getLastName() : string
	{
		return $this->lastName;
	}

	public function setLastName(string $value) : void
	{
		$this->lastName = $value;
	}

	<<String>>
  public function getCompany() : string
	{
		return $this->company;
	}

	public function setCompany(string $value) : void
	{
		$this->company = $value;
	}

	<<String>>
  public function getEmail() : string
	{
		return $this->email;
	}

	public function setEmail(string $value) : void
	{
		$this->email = $value;
	}

	<<String>>
  public function getPhoneNumber() : string
	{
		return $this->phoneNumber;
	}

	public function setPhoneNumber(string $value) : void
	{
		$this->phoneNumber = $value;
	}

	<<DateTime>>
  public function getBirthDate() : ?\DateTime
	{
		return $this->birthDate;
	}

	public function setBirthDate(\DateTime $date) : void
	{
		$this->birthDate = $date;
	}

	<<String>>
  public function getAddress() : string
	{
		return $this->address;
	}

	public function setAddress(string $value) : void
	{
		$this->address = $value;
	}

	<<String>>
  public function getAddress2() : string
	{
		return $this->address2;
	}

	public function setAddress2(string $value) : void
	{
		$this->address2 = $value;
	}

	<<String>>
  public function getCity() : string
	{
		return $this->city;
	}

	public function setCity(string $value) : void
	{
		$this->city = $value;
	}

	<<String>>
  public function getState() : string
	{
		return $this->state;
	}

	public function setState(string $value) : void
	{
		$this->state = $value;
	}

	<<String>>
  public function getCountry() : string
	{
		return $this->country;
	}

	public function setCountry(string $value) : void
	{
		$this->country = $value;
	}

	<<String>>
  public function getCulture() : string
	{
		return $this->culture;
	}

	public function setCulture(string $value) : void
	{
		$this->culture = $value;
	}

	<<String>>
  public function getFullName() : string
	{
		return $this->fullName;
	}

	public function setFullName(string $value) : void
	{
		$this->fullName = $value;
	}

	<<String>>
  public function getGender() : string
	{
		return $this->gender;
	}

	public function setGender(string $value) : void
	{
		$this->gender = $value;
	}

	<<String>>
  public function getLanguage() : string
	{
		return $this->language;
	}

	public function setLanguage(string $value) : void
	{
		$this->language = $value;
	}

	<<String>>
  public function getMailAddress() : string
	{
		return $this->mailAddress;
	}

	public function setMailAddress(string $value) : void
	{
		$this->mailAddress = $value;
	}

	<<String>>
  public function getNickName() : string
	{
		return $this->nickName;
	}

	public function setNickName(string $value) : void
	{
		$this->nickName = $value;
	}

	<<String>>
  public function getPostalCode() : string
	{
		return $this->postalCode;
	}

	public function setPostalCode(string $value) : void
	{
		$this->postalCode = $value;
	}

	<<String>>
  public function getTimeZone() : string
	{
		return $this->timeZone;
	}

	public function setTimeZone(string $value) : void
	{
		$this->timeZone = $value;
	}

	<<Id>>
	public function getId()
	{
		return $this->id;
	}

	<<String>>
	public function getPrimaryEmail() : string
	{
		return $this->primaryEmail;
	}

	public function setPrimaryEmail(string $value) : void
	{
		$this->primaryEmail = $value;
	}

  public function getProvider() : string
  {
    return $this->provider;
  }

  public function setProvider(string $value) : void
  {
    $this->provider = $value;
  }

  public function getUserId()
  {
    return $this->userId;
  }

  public function setUserId($value) : void
  {
    $this->userId = $value;
  }

  public function getUserAuthId()
  {
    return $this->userAuthId;
  }

  public function setUserAuthId($value) : void
  {
    $this->userAuthId = $value;
  }

  public function getAccessToken() : string
  {
    return $this->accessToken;
  }

  public function setAccessToken(string $value) : void
  {
    $this->accessToken = $value;
  }

  public function getAccessTokenSecret() : string
  {
    return $this->accessTokenSecret;
  }

  public function setAccessTokenSecret(string $value) : void
  {
    $this->accessTokenSecret = $value;
  }

  public function getRefreshToken() : string
  {
    return $this->refreshToken;
  }

  public function setRefreshToken(string $value) : void
  {
    $this->refreshToken = $value;
  }

  public function getRefreshTokenExpiry() : ?\DateTime
  {
    return $this->refreshTokenExpire;
  }


  public function setRefreshTokenExpire(\DateTime $value) : void
  {
    $this->refreshToken = $value;
  }

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
    return $this->requestToken;
  }

  public function setRequestTokenSecret(string $value) : void
  {
    $this->requestTokenSecret = $value;
  }
}
