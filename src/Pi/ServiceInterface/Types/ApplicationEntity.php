<?hh

namespace Pi\ServiceInterface\Types;

<<Entity>>
class ApplicationEntity {

	protected $id;

	public function __construct(
		protected string $name,
		protected string $description,
		protected ?\MongoId $ownerId = null)
	{

	}

	//const type = get_class($this);

	<<Id>>
	public function id(\MongoId $id = null)
	{
		if($id === null) return $this->id;
		$this->id = $id;
	}

	<<String>>
	public function hostname($value = null)
	{
		if($value === null) return $this->hostname;
		$this->hostname = $value;
	}

	<<String>>
	public function database($value = null)
	{
		if($value === null) return $this->database;
		$this->database = $value;
	}

	<<String>>
	public function name($value = null)
	{
		if($value === null) return $this->name;
		$this->name = $value;
	}
	
	<<String>>
	public function description($value = null)
	{
		if($value === null) return $this->description;
		$this->description = $value;
	}
}