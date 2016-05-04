<?hh

namespace Pi\ServiceModel;
use Pi\EventArgs;

class PostCommentArgs extends EventArgs {
	
	protected $author;

	protected $message;

	protected $namespace;

	protected $entityId;

    /**
     * Gets the value of accessToken.
     *
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets the value of accessToken.
     *
     * @param mixed $accessToken the access token
     *
     * @return self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Gets the value of reference.
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the value of reference.
     *
     * @param mixed $reference the reference
     *
     * @return self
     */
    public function setMessage($reference)
    {
        $this->message = $reference;

        return $this;
    }

    /**
     * Gets the value of entity.
     *
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Sets the value of entity.
     *
     * @param mixed $entity the entity
     *
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getEntityId()
    {
    	return $this->entityId;
    }

    public function setEntityId($id)
    {
    	$this->entityId = $id;
    }
}