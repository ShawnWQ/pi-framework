<?hh

namespace MarkdownView;

class MarkdownPage {
	
	protected bool $isCompilling;

	public function __construct()
	{
		$this->isCompilling = false;
	}
	
	public function getIsCompilling() : bool
	{
		return $this->isCompilling;
	}
}