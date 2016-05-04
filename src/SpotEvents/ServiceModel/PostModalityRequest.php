<?hh

namespace SpotEvents\ServiceModel;

class PostModalityRequest {

  protected string $title;

  protected string $description;

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
