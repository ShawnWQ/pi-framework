<?hh

namespace Pi\Odm\Mapping;
use Pi\Odm\MappingType;
use Pi\Common\Mapping\AbstractFieldMapping;
use Pi\Odm\CascadeOperation;

class EntityFieldMapping extends AbstractFieldMapping {

  protected $cascade;

  protected $association;

  protected $DBRef = false;
  
  public function setAssociation($association)
  {
    $this->association = $association;
  }

  public function getAssociantion()
  {
    return $this->association;
  }

  public function setDBRef()
  {
    $this->DBRef = true;
  }

  public function isDBRef()
  {
    return $this->DBRef;
  }


  public function getDBType()
  {
    return $this->type;
  }

  public function isCascade()
  {
    return isset($this->cascade);
  }

  public function setCascade(CascadeOperation $type)
  {
    $this->cascade = $type;
  }
  public function getCascade() : CascadeOperation
  {
    return $this->cascade;
  }
}
