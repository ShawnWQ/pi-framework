<?hh

namespace Pi\FileSystem;

class FileGetResponse {
  protected $fileName;

  public function fileName($value = null)
  {
    if($value === null) return $this->fileName;
    $this->fileName = $value;
  }
}
