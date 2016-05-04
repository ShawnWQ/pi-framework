<?hh

namespace Collaboration\ServiceModel;

class PageDto implements \JsonSerializable {

  protected \MongoId $id;

  protected string $navTitle;

	protected string $title;

	protected string $body;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['_id'];
    return $vars;
  }

  <<Id>>
  public function getId()
  {
    return $this->id;
  }

  public function setId(\MongoId $id)
  {
    $this->id = $id;
  }

	<<String>>

	public function getNavTitle()
	{
		return $this->navTitle;
	}

	public function setNavTitle(string $value)
	{
		$this->navTitle = $value;
	}

	<<String>>
	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle()
	{
		$this->title = $title;
	}

	<<String>>
	public function getBody()
	{
		return $this->body;
	}

	public function setBody(string $value)
	{
		$this->body = $value;
	}
}
