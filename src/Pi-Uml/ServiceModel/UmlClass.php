<?hh

namespace Pi\Uml\ServiceModel;
use Pi\Common\ClassUtils;

class UmlClass implements \JsonSerializable{

  protected $shortName;

  public function __construct(protected $name, protected $methods = array(), protected $dependencies = array())
  {
    $this->shortName = ClassUtils::getClassRealname($name);
  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
}
