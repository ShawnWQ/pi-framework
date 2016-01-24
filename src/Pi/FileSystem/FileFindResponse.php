<?hh

namespace Pi\FileSystem;

use Pi\Response;




class FileFindResponse extends Response {

  public function __construct(
    protected $files
  )
  {

  }

  public function getFiles()
  {
    return $this->files;
  }
}
