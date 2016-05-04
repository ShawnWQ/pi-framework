<?hh

namespace Pi\ServiceModel;

/**
 * Auth DTO accessed through Request
 */
class AuthSession {

	public function __construct(protected \MongoId $id, protected string $displayName)
	{

	}

	public function getName()
	{
		return $this->displayName;
	}

	public function getId()
	{
		return $this->id;
	}
}
