<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

class ContactEmbed implements \JsonSerializable, IEntity {
	
	protected string $emailPublic;

	protected string $phone;

	protected string $mobile;

	public function jsonSerialize()
	{
		$vars = get_object_vars($this);
		return $vars;
	}

	public function getEmailPublic()
	{
		return $this->emailPublic;
	}

	public function setEmailEmbed(string $value)
	{
		$this->value = $value;
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function setPhone(string $value)
	{
		$this->phone = $value;
	}

	public function getMobile()
	{
		return $this->mobile;
	}

	public function setMobile(string $value)
	{
		$this->mobile = $value;
	}
}