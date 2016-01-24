<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<Collection('newsletter-subs')>>
class NewsletterSubscription implements IEntity, \JsonSerializable {

	protected $id;

    /**
     * @var string
     */
    protected string $name;

    protected $reference;

    protected string $email;

    public function jsonSerialize()
    {
        $arr = get_object_vars($this);
        $arr['id'] = (string)$arr['id'];
        return $arr;
    }

    public function setReference(string $ref)
    {
        $this->reference = $ref;
    }

    public function getReference()
    {
        return $this->reference;
    }

	<<Id>>
	public function id($value = null)
	{
		if($value === null) return $this->id;
		$this->id = $value;
	}


    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    <<String>>
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    <<String>>
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setEmail($name)
    {
        $this->email = $name;

        return $this;
    }
}
