<?hh

namespace Pi\Uml\ServiceModel;

class UmlGenerateRequest {
	
	protected $path;

	protected $recursive;

	public function paths($value = null)
	{
		if($value === null) return $this->path;
		$this->path = $value;
	}

	public function recursive($value = null)
	{
		if($value === null) return $this->recursive;
		$this->recursive = $value;
	}
}