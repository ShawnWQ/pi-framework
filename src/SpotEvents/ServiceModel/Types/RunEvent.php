<?hh

namespace SpotEvents\ServiceModel\Types;




class RunEvent extends EventEntity {
	
	protected array $checkpoints;

	public function getCheckpoints()
	{
		return $this->checkpoints;
	}

	public function setCheckpoints(array $checkpoints)
	{
		$this->checkpoints = $checkpoints;
	}

}