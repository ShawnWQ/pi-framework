<?hh

namespace Pi\ServiceModel;

class FindArticleSerieRequest extends RequestQueryAbstract {

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