<?hh

namespace Mocks;
use Pi\Odm\Interfaces\IEntity;
use Pi\Odm\MongoEntity;

<<Collection('fas2'),InheritanceType('Single'),DiscriminatorField('type')>>
abstract class EntityInheritedAbstract implements IEntity {

  public $name;

  public $address;

  public $id;

  public ?\MongoId $two;

    protected $tokens = array();

  public Vector<MockEntity2Emb> $twoRef = Vector{};

  public ?array $twoDBRef;

  protected array $inscriptions;

  protected $appId;

  protected ?\DateTime $when;

  protected $counter;

  protected $type;

  <<String>>
  public function getType()
  {
    return $this->type;
  }

  public function setType($value)
  {
    $this->type = $value;
  }

  <<Id>>
  public function id($value = null)
  {
    if($value === null) return $this->id;
    $this->id = $value;
  }

  public function appId($value = null)
  {
    if($value === null) return $this->appId;
    $this->appId = $value;
  }

  <<Collection>>
  public function tokens(array $values = null)
  {
      if($values === null) return $this->tokens;
      $this->tokens = $values;
  }

  <<EmbedMany('Mocks\MockEntity2')>>
  public function inscriptions(array $values = null)
  {
      if($values === null) return $this->inscriptions;
      $this->inscriptions = $values;
  }

  <<DBRef>>
  public function twoDBRef(?array $value = null)
  {
    if($value === null) return $this->twoDBRef;
    $this->twoDBRef = $value;
  }

  <<Reference>>
  public function twoRef(Vector<MockEntity2Emb> $value = null)
  {
    if($value === null) return $this->twoRef;
    $this->twoRef = $value;
  }

  <<Reference>>
  public function two(?\MongoId $value = null)
  {
    if($value === null) return $this->two;
    $this->two = $value;
  }

  <<String,MinLength(400)>>
  public function name($value = null)
  {
      if($value === null) return $this->name;
      $this->name = $value;
  }

  <<String,MinLength(400)>>
  public function address($value = null)
  {
      if($value === null) return $this->address;
      $this->address = $value;
  }

  <<Datetime>>
  public function when(\Datetime $value = null)
  {
      if($value === null) return $this->when;
      $this->when = $value;
  }

  <<Int>>
  public function counter($value = null)
  {
      if($value === null) return $this->counter;
      $this->counter = $value;
  }
}
