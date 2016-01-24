<?hh

namespace Pi\Odm\Mapping;
use Pi\Odm\MappingType;
use Pi\Common\Mapping\AbstractFieldMapping;
use Pi\Odm\CascadeOperation;

class EntityFieldMapping extends AbstractFieldMapping {

  protected $cascade;

  protected $embeded;

  protected $embedOne = false;

  protected $embedMany = false;

  protected $embedType;

  protected $referenceOne = false;

  protected $referenceMany = false;

  protected $association;

  protected $DBRef = false;

  protected $array = false;

  protected $dateTime = false;

  protected $notNull = false;

  protected $timestamp = false;

  protected $int = false;

  protected ?string $defaultDiscriminatorValue;

  protected ?string $inheritanceType;

  protected ?string $discriminatorField;

  public function setIsInt() : void
  {
    $this->int = true;
  }

  public function getIsInt() : bool
  {
    return $this->int;
  }

  public function getDefaultDiscriminatorValue() : ?string
  {
    return $this->defaultDiscriminatorValue;
  }

  public function setDefaultDiscriminatorValue(string $value) : void
  {
    $this->difaultDescriminatorValue = $value;
  }

  public function getInheritanceType() : ?string
  {
    return $this->inheritanceType;
  }

  public function setInheritanceType(string $value) : void
  {
    $this->inheritanceType = $value;
  }

  public function getDiscriminatorField() : ?string
  {
    return $this->discriminatorField;
  }

  public function setDiscriminatorField(string $value) : void
  {
    $this->discriminatorField = $value;
  }

  public function isNotNull() : bool
  {
    return $this->notNull;
  }

  public function setIsNotNull(bool $isNull) : void
  {
    $this->notNull = $isNull;
  }

  public function setDateTime()
  {
    $this->dateTime = true;
  }

  public function isDateTime()
  {
    return $this->dateTime;
  }

  public function setArray()
  {
    $this->array = true;
  }

  public function isArray()
  {
    return $this->array;
  }

  public function setAssociation($association)
  {
    $this->association = $association;
  }

  public function getAssociantion()
  {
    return $this->association;
  }

  public function setReferenceOne()
  {
    $this->referenceOne = true;
  }

  public function getReferenceOne()
  {
    return $this->referenceOne;
  }

  public function setReferenceMany()
  {
    $this->referenceMany = true;
  }

  public function isReferenceMany()
  {
    return $this->referenceMany;
  }

  public function setEmbedOne()
  {
    $this->embedOne = true;
  }

  public function setEmbedType($type)
  {
    $this->embedType = $type;
  }

  public function getEmbedType()
  {
    return $this->embedType;
  }

  public function isEmbedOne()
  {
    return $this->embedOne;
  }

  public function setEmbedMany($type)
  {
    $this->embedMany = true;
    $this->embedType = $type;
  }

  public function isEmbedMany()
  {
    return $this->embedMany;
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

  public function setTimestamp(bool $value) : void
  {
    $this->timestamp = $value;
  }

  public function isTimestamp() : bool
  {
    return $this->timestamp;
  }
}
