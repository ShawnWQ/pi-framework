<?hh

namespace Pi\FileSystem;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;

class FileSystemPlugin implements IPlugin {

  public function __construct(protected ?FileSystemConfiguration $config = null)
  {
    if($this->config === null){
      $this->config = new FileSystemConfiguration();
      $path = sys_get_temp_dir();
      $this->config->storeDir($path);
    }
  }

  public function configure(IPiHost $host) : void
  {
    $config = $this->config;
    $host->container()->register('FileSystemConfiguration', function() use($config){
      return $config;
    });
    $host->container()->registerAlias('Pi\FileSystem\FileSystemConfiguration', 'FileSystemConfiguration');
    $host->registerService(new FileSystemService());

    $host->container()->registerRepository(new FileEntity(), new FileEntityRepository());
  }
}
