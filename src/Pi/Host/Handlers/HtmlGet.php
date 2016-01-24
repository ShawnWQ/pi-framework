<?hh

namespace Pi\Host\Handlers;

class HtmlGet {

	public function __construct(protected string $fileName)
	{

	}

	public function fileName($value = null)
	{
		if($value === null) return $this->fileName;
		$this->fileName = $value;
	}
}