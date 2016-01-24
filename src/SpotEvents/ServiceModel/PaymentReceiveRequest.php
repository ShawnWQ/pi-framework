<?hh

namespace SpotEvents\ServiceModel;
use Pi\EventArgs;

class PaymentReceiveRequest extends EventArgs {
	
	protected $accessToken;

	protected $reference;

	protected $entity;

    /**
     * Gets the value of accessToken.
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Sets the value of accessToken.
     *
     * @param mixed $accessToken the access token
     *
     * @return self
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Gets the value of reference.
     *
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Sets the value of reference.
     *
     * @param mixed $reference the reference
     *
     * @return self
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Gets the value of entity.
     *
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets the value of entity.
     *
     * @param mixed $entity the entity
     *
     * @return self
     */
    protected function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}