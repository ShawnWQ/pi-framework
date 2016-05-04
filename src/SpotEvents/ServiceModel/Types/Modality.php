<?hh

namespace SpotEvents\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<Collection('modality')>>
class Modality implements IEntity {
  
  protected \MongoId $id;

  protected string $title;

  protected string $description;

  <<Id>>
  public function id($value = null)
  {
  	if(is_null($value)) return $this->id;
  	$this->id = $value;
  }

  <<String>>
  public function getTitle() : string
  {
  	return $this->title;
  }

  public function setTitle(string $value) : void
  {
  	$this->title = $value;
  }

  <<String>>
  public function getDescription() : string
  {
  	return $this->description;
  }

  public function setDescription(string $value) : void
  {
  	$this->description = $value;
  }
}
