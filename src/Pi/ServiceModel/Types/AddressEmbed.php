<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

class AddressEmbed implements \JsonSerializable, IEntity{
	
	protected string $city;

	protected string $address;

	protected string $cep;

	protected string $country;

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}

	<<String>>
	public function getCity() : string
	{
		return $this->city;
	}

	public function setCity(string $city) : void
	{
		$this->city = $city;
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
	public function getCep() : string
	{
		return $this->cep;
	}

	public function setCep(string $value) : void
	{
		$this->cep = $value;
	}

	public function getCountry() : string
	{
		return $this->country;
	}

	public function setCountry(string $value) : void
	{
		$this->country = $value;
	}
}