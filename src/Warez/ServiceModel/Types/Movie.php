<?hh

namespace Warez\ServiceModel\Types;
use Pi\Odm\Interfaces\IEntity;

<<Entity,Collection('movie')>>
class Movie implements IEntity, \JsonSerializable {
  
  protected \MongoId $id;

  protected string $title;

  protected string $year;

  protected string $movieUri;

  protected string $imdbId;

  protected string $coverSrc;

  protected string $captionSrc;

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['id'] = (string)$vars['id'];
    return $vars;
  }

  <<Id>>
  public function id($value = null)
  {
    if($value === null) return $this->id;
    $this->id = $value;
  }

  <<String>>
  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle(string $title)
  {
    $this->title = $title;
  }

  <<String>>
  public function getYear()
  {
    return $this->year;
  }

  public function setYear($value)
  {
    $this->year = $value;
  }

  <<String>>
  public function getMovieUri()
  {
    return $this->movieUri;
  }

  public function setMovieUri(string $uri)
  {
    $this->movieUri = $uri;
  }

  <<String>>
  public function getImdbId()
  {
    return $this->imdbId;
  }

  public function setImdbId(string $id)
  {
    $this->imdbId = $id;
  }

  <<String>>
  public function getCoverSrc()
  {
    return $this->coverSrc;
  }

  public function setCoverSrc(string $src)
  {
    $this->coverSrc = $src;
  }

  <<String>>
  public function getCaptionSrc()
  {
    return $this->captionSrc;
  }

  public function setCaptionSrc(string $value)
  {
    $this->captionSrc = $value;
  }

}
