<?hh

namespace Warez\ServiceModel;

class PutMovieRequest {

  protected \MongoId $id;

  protected string $title;

  protected string $year;

  protected string $movieUri;

  protected string $imdbId;

  protected string $coverSrc;

  protected string $captionSrc;

  <<ObjectId>>
  public function getId()
  {
    return $this->id;
  }

  public function setId(\MongoId $id)
  {
    $this->id = $id;
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
