<?hh

namespace Pi\ServiceModel\Types;
use Pi\Interfaces\IFeed;

<<EmbeddedDocument>>
class FriendCommonFeed extends UserFeed implements IFeed {
	protected FeedType $type;

	protected string $text;


	public function setText(string $value)
	{
		$this->text = $value;
	}
	public function getText()
	{
		return $this->return;
	}
	<<String>>
	public function getType()
	{
		return $this->type;
	}

	public function setType($value)
	{
		$this->type = $value;
	}
	protected $friend;

	public function getFriend()
	{
		return $this->friend;
	}

	public function setFriend($friend)
	{
		$this->friend = $friend;
	}
}