<?hh

namespace Mocks;

class DumbRequest {

	public function __construct($name = 'asd', $description = 'asdasd')
	{

	}

	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
}