<?hh

namespace SpotEvents\ServiceModel;

use Pi\ServiceModel\RequestQueryAbstract;

class FindWorkoutSerieRequest extends RequestQueryAbstract {

	protected string $name;

	<<ObjectId>>
	public function getName() : ?string
	{
		return $this->name;
	}

	public function setName(string $value) : void
	{
		$this->name = $value;
	}
}
