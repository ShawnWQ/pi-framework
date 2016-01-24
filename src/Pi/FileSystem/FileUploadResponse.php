<?hh

namespace Pi\FileSystem;

class FileUploadResponse implements \JsonSerializable {

  protected $uri;

  protected $uriPublic;

  protected $ownerToken;

  protected $fileId;

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
  public function uri($value = null)
  {
    if($value === null) return $this->uri;
    $this->uri = $value;
  }

  public function fileId($value = null)
  {
    if($value === null) return $this->fileId;
    $this->fileId = $value;
  }

  public function ownerToken($value = null)
  {
    if($value === null) return $this->ownerToken;
    $this->ownerToken = $value;
  }

  public function uriPublic($value = null)
  {
    if($value === null) return $this->uriPublic;
    $this->uriPublic = $value;
  }
}
