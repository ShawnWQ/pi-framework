<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/23/15
 * Time: 5:44 PM
 */

namespace Pi\ServiceModel;


class ListFilesRequest {
    /**
     * @return array
     */
    <<Collection>>
    public function getFilesType()
    {
        return $this->filesType;
    }

    /**
     * @param array $filesType
     */
    public function setFilesType($filesType)
    {
        $this->filesType = $filesType;
    }
    /**
     * @return string
     */
    public function getDirPath()
    {
        return $this->dirPath;
    }

    /**
     * @param string $dirPath
     */
    public function setDirPath($dirPath)
    {
        $this->dirPath = $dirPath;
    }

    /**
     * @var string
     */
    protected string $dirPath;

    /**
     * @var array
     */
    protected $filesType;
}