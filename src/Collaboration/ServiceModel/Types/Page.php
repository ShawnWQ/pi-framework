<?hh


namespace Collaboration\ServiceModel\Types;
use Pi\ServiceModel\Types\Article;


class Page extends Article {

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
