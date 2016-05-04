<?hh

namespace Pi\ServiceModel\Types;

abstract class AbstractNotification {

	protected $readed;

	protected Author $actor;

	protected $action;

	protected $receptor;

	public function getAction()
	{
		return $this->action;
	}

	public function setAction($action)
	{
		$this->action = $action;
	}

	public function getReceptor()
	{
		return $this->receptor;
	}

	public function setReceptor($receptor)
	{
		$this->receptor = $receptor;
	}

	public function getActor()
	{
		return $this->actor;
	}

	public function setActor(Author $actor)
	{
		$this->actor = $actor;
	}

	public function isReaded()
	{
		return $this->readed;
	}

	public function setReaded($value)
	{
		$this->readed = $value;
	}
}