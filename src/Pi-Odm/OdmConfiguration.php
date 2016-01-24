<?hh

namespace Pi\Odm;
use Pi\Odm\Driver\AbstractMappingDriver;
use Pi\Odm\Interfaces\IMappingDriver;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class OdmConfiguration implements IContainable {

  protected $hydratorDir = '/tmp/pi-tmp';

  protected $hydratorNamespace;

  protected $autoGenerateHydratorClasses;

  protected $defaultDb;

  protected $metadataDriverImplementation;

  protected $multiTenantMode = false;

  public function ioc(IContainer $container) 
  {

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

  public function getDefaultDb()
  {
    return $this->defaultDb;
  }

  public function getAutoGenerateHydratorClasses()
  {
    return $this->autoGenerateHydratorClasses;
  }

  public function setAutoGenerateHydratorClasses(bool $value)
  {
    $this->autoGenerateHydratorClasses = $value;
  }

  public function setDefaultDb(string $databaseName)
  {
    $this->defaultDb = $databaseName;
  }

  public function getMetadataDriverImplementation()
  {
    return $this->metadataDriverImplementation;
  }

  public function setMetadataDriverImplementation(IMappingDriver $driver)
  {
    $this->metadataDriverImplementation = $driver;
  }

  public function getMultiTenantMode()
  {
    return $this->multiTenantMode;
  }

  public function setMultiTenantMode(bool $enabled)
  {
    $this->multiTenantMode = $enabled;
  }
}
