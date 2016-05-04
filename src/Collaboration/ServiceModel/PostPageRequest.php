<?hh

namespace Collaboration\ServiceModel;

class PostPageRequest {

  protected string $navTitle;

	protected string $title;

	protected string $body;

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

	public function setTitle($title)
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
