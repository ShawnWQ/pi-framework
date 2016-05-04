<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class BasicRegisterResponse extends Response {
  
  protected $id;

  public function setId(\MongoId $id)
  {
  	$this->id = $id;
  }

  public function getId()
  {
  	return $this->id;
  }
}
