<?hh

namespace Pi\ServiceModel;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection("users")>>
class UserDto implements IEntity, \JsonSerializable {

	protected  $id;

	protected string $displayName;

	protected string $email;

	protected $contact;

	protected $address;


	<<EmbedOne('Pi\ServiceModel\Types\ContactEmbed')>>
	public function getContact()
	{
		return $this->contact;
	}

	public function setContact($contact)
	{
		$this->contact = $contact;
	}

	<<EmbedOne('Pi\ServiceModel\Types\AddressEmbed')>>
	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	public function jsonSerialize()
	{
		$dto = get_object_vars($this);
		$dto['id'] = (string)$dto['id'];
		return $dto;
	}

	<<String>>
	public function getDisplayName()
	{
		return $this->displayName;
	}

	public function setDisplayName(string $value)
	{
		$this->displayName = $value;
	}

	<<String>>
	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail(string $value)
	{
		$this->email = $value;
	}

	<<Id>>
	public function id($value = null)
	{
		if(is_null($value)) return $this->id;
		$this->id = $value;
	}

	public function getId()
	{
		return $this->id;
	}
}