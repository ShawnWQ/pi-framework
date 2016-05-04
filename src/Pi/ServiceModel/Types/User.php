<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection("users")>>
class User implements IEntity {

	protected  $id;

	protected string $displayName;

	protected $albuns;

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

	<<Collection>>
	public function getAlbuns()
	{
		return $this->albuns;
	}

	public function setAlbuns(string $albuns)
	{
		$this->albuns = $albuns;
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

	public function getId()
	{
		return $this->id;
	}

	<<Id>>
	public function id($value = null)
	{
		if(is_null($value)) return $this->id;
		$this->id = $value;
	}
}