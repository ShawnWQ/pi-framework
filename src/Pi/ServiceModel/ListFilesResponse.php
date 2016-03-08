<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/23/15
 * Time: 5:45 PM
 */

namespace Pi\ServiceModel;


use Pi\Response;

class ListFilesResponse extends Response{

    protected $files;

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
}