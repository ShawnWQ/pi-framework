<?hh

namespace Pi\ServiceModel;

class GetBatchOperation {
	
	protected $operations;

	<<Collection>>
	public function getOperations()
	{
		return $this->operations;
	}

	public function setOperations(array $operations)
	{
		$this->operations = $operations;
	}
}