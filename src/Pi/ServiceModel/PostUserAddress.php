<?hh

namespace Pi\ServiceModel;

class PostUserAddress {
	
	protected string $city;

	protected string $address;

	protected string $cep;

	protected string $country;

	protected \MongoId $id;

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
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

	<<String>>
	public function getCountry() : string
	{
		return $this->country;
	}

	public function setCountry(string $value) : void
	{
		$this->country = $value;
	}
}