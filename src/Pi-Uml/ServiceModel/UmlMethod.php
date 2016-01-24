<?hh

namespace Pi\Uml\ServiceModel;

class UmlMethod implements \JsonSerializable{

  public function __construct(protected $name, protected $returnType, array $params = array())
  {

  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
