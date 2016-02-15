<?hh

namespace Pi;

use Pi\Interfaces\IHostConfig;
use Pi\ServiceModel\UrlSchemaType;

/**
 * Application host configuration
 */
class HostConfig
  implements IHostConfig {

  protected $configsPath;

  protected $cacheFolder;

  protected $loggerFolder;

  protected $defaultContentType = 'text/json';

  protected $domain = 'localhost';

  protected $staticFolder;

  protected $appId;

  

  protected $webHostPhysicalPath;

  protected $protocol = 'http';

  protected string $baseUri;

  protected Set<string,array> $oAuths;

  protected int $urlSchema = 0;

  protected string $hydratorDir;

  protected string $hydratorNamespace;

  protected $autoGenerateHydratorClasses;

  protected $smtpFromName;

  protected $smtpFromEmail;

  protected $smtpHost;

  protected $smtpPort;

  protected ?string $smtpUsername;

  protected ?string $smtpPassword;

  protected ?string $smtpSsl;

  protected string $appName;

  public function __construct()
  {
    $bt = debug_backtrace();
    $this->appName = 'Pi';
    //$path = $bt[count($bt)-1]['file'];
    $path = sys_get_temp_dir() . '/pi-tmp';
    $this->oAuths = Set{};
    $this->configsPath = $path . '/pi-config.json';
    $this->cacheFolder = $path . '/pi-config.json';
    $this->loggerFolder = $path . '/pi-log.json';
    $this->staticFolder = $path . '/static';
    $this->urlSchema = UrlSchemaType::Normal;
    $this->baseUri = '/api';
    $this->hydratorDir = $path;
    $this->hydratorNamespace = 'Mocks\\Hydrators';
    $this->smtpHost = 'localhost';
    $this->smtpPort = 456;
    $this->smtpFromName = 'Guilherme Cardoso';
    $this->smtpFromEmail = 'email@guilhermecardoso.pt';
    $this->smtpUsername = null;
    $this->smtpPassword = null;
    $this->smtpSsl = null;
  }

  public function smtp(string $host, string $port, string $fromEmail, string $fromName, ?string $username = null, ?string $password = null)
  {
    $this->smtpHost = $host;
    $this->smtpPort = $port;
    $this->smtpFromEmail = $fromEmail;
    $this->smtpFromName = $fromEmail;
    
    if(!is_null($username)) {
      $this->smtpUsername = $username;
    }

    if(!is_null($password)) {
      $this->smtpPassword = $password;
    }
  }

  public function smtpFromName()
  {
    return $this->smtpFromName;
  }

  public function smtpFromEmail()
  {
    return $this->smtpFromEmail;
  }

  public function smtpPort() : int
  {
    return $this->smtpPort;
  }

  public function smtpHost() : string
  {
    return $this->smtpHost;
  }

  public function smtpUsername() : ?string
  {
    return $this->smtpUsername;
  }

  public function smtpPassword() : ?string
  {
    return $this->smtpPassword;
  }

  public function smtpSsl() : ?string
  {
    return $this->smtpSsl;
  }

  public function hydratorDir()
  {
    return $this->hydratorDir;
  }

  public function getHydratorDir()
  {
    return $this->hydratorDir;
  }

  public function setHydratorDir(string $dir)
  {
    $this->hydratorDir = $dir;
  }

  public function getHydratorNamespace()
  {
    return $this->hydratorNamespace;
  }

  public function setHydratorNamespace(string $namespace)
  {
    $this->hydratorNamespace = $namespace;
  }
  
  public function getAutoGenerateHydratorClasses()
  {
    return $this->autoGenerateHydratorClasses;
  }

  public function setAutoGenerateHydratorClasses(bool $value)
  {
    $this->autoGenerateHydratorClasses = $value;
  }
  
  public function webHostPhysicalPath(string $values = null)
  {
    if(is_null($values)) return $this->webHostPhysicalPath;
    $this->webHostPhysicalPath = $values;
  }

  public function urlSchema(int $value = null)
  {
    if(is_null($value)) return $this->urlSchema;
    $this->urlSchema = $value;
  }


  public function oAuths(?array $values = null) : mixed
  {
    if(is_null($values)) return $this->oAuths;
    $this->oAuths = $values;
  }

  public function get(string $key, ?string $default = null)
  {
    return !is_null($default) ? $default : '';
  }

  public function protocol($value = null)
  {
    if($value === null) return $this->protocol;
    $this->protocol = $value;
  }

  public function staticFolder($value = null)
  {
    if($value === null) return $this->staticFolder;
    $this->staticFolder = $value;
  }

  public function baseUri($value = null)
  {
    if($value === null) return $this->baseUri;
    $this->baseUri = $value;
  }


  public function defaultContentType($value = null)
  {
    if($value === null) return $this->defaultContentType;
    else $this->defaultContentType = $value;
  }

  public function configsPath($value = null)
  {
    if($value === null) return $this->configsPath;
    $this->configsPath = $value;
  }

  public function cacheFolder($value = null)
  {
    if($value === null) return $this->cacheFolder;
    $this->cacheFolder = $value;
  }

  public function loggerFolder($value = null)
  {
    if($value === null) return $this->loggerFolder;
    $this->loggerFolder = $value;
  }

  public function getAppId()
  {
    return $this->appId;
  }

  public function appId($value = null)
  {
    if($value === null) return $this->appId;
    $this->appId = $value;
  }

  public function getConfigsPath() : string
  {
    return $this->configsPath;
  }

  public function domain($value = null)
  {
    if($value === null) return $this->domain;
    else $this->domain = $value;
  }

  public function setConfigsPath(string $path)
  {
    $this->configsPath = $path;
  }
}
