<?hh

namespace Pi\ServiceModel\Types;

use Pi\Interfaces\IFeed;

<<EmbeddedDocument>>
class UserFeed extends AbstractFeed implements IFeed {

	protected FeedType $type;

	protected string $text;


	public function setText(string $value)
	{
		$this->text = $value;
	}

	<<String>>
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

}
