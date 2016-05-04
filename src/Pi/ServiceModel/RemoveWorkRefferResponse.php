<?hh

namespace Pi\ServiceModel;

use Pi\Response;




class RemoveWorkRefferResponse extends Response {
	
	protected \MongoId $id;

	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}
}