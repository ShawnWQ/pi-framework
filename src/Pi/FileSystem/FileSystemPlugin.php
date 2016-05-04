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

  public function register(IPiHost $host) : void
  {
    $config = $this->config;
    $host->container()->register(FileSystemConfiguration::class, function() use($config){
      return $config;
    });
    $host->container()->registerAlias(FileSystemConfiguration::class, 'FileSystemConfiguration');
    $host->registerService(FileSystemService::class);

    $host->container()->registerRepository(FileEntity::class, FileEntityRepository::class);
  }
}
