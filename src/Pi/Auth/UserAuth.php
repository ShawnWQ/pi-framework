<?hh

namespace Pi\Auth;
use Pi\Odm\Interfaces\IEntity;
use Pi\Auth\Interfaces\IUserAuth;
use Pi\Auth\Interfaces\IUserAuthDetailsExtended;

<<Entity,Collection("users")>>
class UserAuth implements IEntity, IUserAuth {

	protected $id;

	protected $firstName;

	protected $lastName;

	protected $email;

	protected $password;

	protected $displayName;

  protected $state;

	protected string $userName;

	protected string $company;

	protected string $phoneNumber;

	protected \DateTime $birthDate;

	protected string $address;

	protected string $address2;

	protected string $city;

	protected string $country;

	protected string $culture;

	protected string $fullName;

	protected string $gender;

	protected string $language;

	protected string $mailAddress;

	protected string $nickName;

	protected string $postalCode;

	protected string $timeZone;

	protected string $primaryEmail;

	protected string $salt;

	protected string $digestHalt;

	protected string $passwordHash;

	protected array $permissions;

	protected array $roles;

	protected string $refId;

	protected $invalidLoginAttempt;

	protected ?\DateTime $lastLoginAttempt;

	protected \DateTime $lockedDate;

	protected \DateTime $createdDate;

	protected \DateTime $modifiedDate;

	protected array $meta;

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

	<<String>>
	public function getSalt() : string
	{
		return $this->salt;
	}

	public function setSalt(string $value) : void
	{
		$this->salt = $value;
	}

	<<String>>
	public function getDigestHalt() : string
	{
		return $this->digestHalt;
	}

	public function setDigestHalt(string $value) : void
	{
		$this->digestHalt = $value;
	}

	<<String>>
	public function getPasswordHash() : string
	{
		return $this->passwordHash;
	}

	public function setPasswordHash(string $value) : void
	{
		$this->passwordHash = $value;
	}

	<<Collection>>
	public function getPermissions() : array
	{
		return $this->permissions;
	}

	public function setPermissions(array $value) : void
	{
		$this->permissions = $value;
	}

	<<Collection>>
	public function getRoles() : array
	{
		return $this->roles;
	}

	public function setRoles(array $value) : void
	{
		$this->roles = $value;
	}

	<<String>>
	public function getRefId() : string
	{
		return $this->refId;
	}

	<<Int>>
	public function getInvalidLoginsAttempt() : int
	{
		return $this->invalidLoginAttempt;
	}

	public function setInvalidLoginAttempt(int $value) : void
	{
		$this->invalidLoginAttempt = $value;
	}

	<<DateTime>>
  public function getLastLoginAttempt() : ?\DateTime
	{
		return $this->lastLoginAttempt;
	}

	public function setLastLoginAttempt(\DateTime $when) : void
	{
		$this->lastLoginAttempt = $when;
	}

	<<DateTime>>
  public function getLockedDate() : ?\DateTime
	{
		return $this->lockedDate;
	}

	public function setLockedDate(\DateTime $date) : void
	{
		$this->locked = $date;
	}

	<<DateTime>>
  public function getCreatedDate() : \DateTime
	{
		return $this->createdDate;
	}

	public function setCreatedDate(\DateTime $date) : void
	{
		$this->createdDate = $date;
	}

	<<DateTime>>
  public function getModifiedDate() : \DateTime
	{
		return $this->modifiedDate;
	}

	public function setModifiedDate(\DateTime $date) : void
	{
		$this->modifiedDate = $date;
	}


	<<Collection>>
	public function getMeta() : array
	{
		return $this->meta;
	}

	public function setMeta(array $values) : void
	{
		$this->meta = $values;
	}

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}
	<<String>>
	public function firstName($value = null)
	{
		if($value === null) return $this->firstName;
		$this->firstName = $value;
	}
	<<String>>
	public function lastName($value = null)
	{
		if($value === null) return $this->lastName;
		$this->lastName = $value;
	}
	<<String>>
	public function email($value = null)
	{
		if($value === null) return $this->email;
		$this->email = $value;
	}
	<<String>>
	public function password($value = null)
	{
		if($value === null) return $this->password;
		$this->password = $value;
	}

	<<String>>
	public function displayName($value = null)
	{
		if($value === null) return $this->displayName;
		$this->displayName = $value;
	}

    <<Int>>
    public function state($value = null)
    {
        if($value === null) return $this->state;
        $this->state = $value;
    }
}
