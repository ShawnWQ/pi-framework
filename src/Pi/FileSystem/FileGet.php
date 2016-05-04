<?hh

namespace Pi\FileSystem;

class FileGet {

  protected $fileId;

  protected $name;

  protected $asAttachment;

  <<Bool>>
  public function asAttachment($value = null)
  {
    if($value === null) return $this->asAttachment;
    $this->asAttachment = $value;
  }

  <<MongoId>>
  public function fileId($value = null)
  {
    if($value === null) return $this->fileId;
    $this->fileId = $value;
  }

  <<String>>
  public function name($value = null)
  {
    if($value === null) return $this->name;
    $this->name = $value;
  }
}
