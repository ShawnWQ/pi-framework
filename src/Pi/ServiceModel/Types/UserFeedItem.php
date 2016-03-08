<?hh

namespace Pi\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<Entity>>
class UserFeedItem extends AbstractFeed implements IEntity, \JsonSerializable {

	protected \MongoId $userId;

	protected \MongoId $actionId;

	protected \MongoId $id;

	protected \DateTime $relevancy;

	public function jsonSerialize()
	{
		$vars = get_object_vars($this);
		return $vars;
	}

	<<Id>>
	public function id($id = null)
	{
		if($id === null) return $this->id;
		$this->id = $id;
	}

	<<ObjectId>>
	public function actionId($id = null)
	{
		if($id === null) return $this->actionId;
		$this->actionId = $id;
	}

	<<ObjectId>>
	public function getUserId()
	{
		return $this->id;
	}

	public function setUserId(\MongoId $id)
	{
		$this->userId = $id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	<<DateTime>>
	public function getRelevancy()
	{
		return $this->relevancy;
	}

	public function setRelefancy($relevancy)
	{
		$this->relevancy = $relevancy;
	}

}
