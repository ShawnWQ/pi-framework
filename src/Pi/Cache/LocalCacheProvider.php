<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IContainer;




/**
 * Local Cache Provider
 * Persists the cache data to a file
 */
class LocalCacheProvider implements ICacheProvider{

  protected $config;

  public function __construct(protected $filePath)
  {
    if(!is_writable($this->filePath)){
      throw new \Exception(
        sprintf('The LocalCacheProvider save directory isnt writable. Fix permissions for: %s', $this->filePath));
    }

    $file = fopen($this->filePath, 'w');
    $size = filesize($filePath);
    if($size <= 1) {
      $this->createFile();
    } else {
      $this->readFile($file, $this->filePath);
    }
  }

  public function ioc(IContainer $container) { }

  private function readFile($file, $filePath) : void
  {
    try {
      $text = fread($file, filesize($filePath));
      $this->config = json_decode($text);
    }
    catch(\Exception $ex) {
      throw new \Exception(
        sprintf('The cache file couldnt be propery deserialized. fix the configuration file for cache: %s', $ex->getMessage())
      );
    }
    fclose($file);
  }

  private function createFile() : void
  {
    $dto = json_decode(json_encode(array('version' => 'dev')));
    $this->config = $dto;
  }

  public function get($key = null)
  {
    if(is_null($key))
      return $this->config;

    return array_key_exists($key, $this->config) ? $this->config->$key : null;
  }

  protected function persist()
  {
    file_put_contents($this->filePath, json_encode($this->config));
  }

  public function set($key, $value, $persist = true)
  {
    try {
      $this->config->$key = $value;
      if($persist) {
        $this->persist();
      }
    }
    catch(\Exception $ex) {
      throw new \Exception(
        sprintf('Error while writting a new value in local cache provider: %s', $ex->getMessage())
      );
    }

  }

  public function push($key, $value, $persist = true)
  {
    try {
      if(!array_key_exists($key, $this->config)) {
        $this->config->$key = Set{};
      }
      $this->config->$key->add($value);
      
      if($persist) {
        $this->persist();
      }
    }
    catch(\Exception $ex) {
      throw new \Exception(
        sprintf('Error while pushing a new value in local cache provider: %s', $ex->getMessage())
      ); 
    }

  }
}
