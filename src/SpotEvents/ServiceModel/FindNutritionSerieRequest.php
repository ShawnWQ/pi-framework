<?hh

namespace SpotEvents\ServiceModel;

use Pi\ServiceModel\RequestQueryAbstract;

class FindNutritionSerieRequest extends RequestQueryAbstract {

	protected string $name;

	<<String>>
	public function getName() : ?string
	{
		return $this->name;
	}

	public function setName(string $value) : void
	{
		$this->name = $value;
	}
}
