<?hh

namespace Pi\Odm;

use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\Odm\Hydrator\MongoDBHydratorFactory;
use Pi\Odm\UnitWork;
use Pi\Odm\MongoManager;
use Pi\PiContainer;
use Pi\EventManager;
use Pi\Odm\DocumentManager;
use Pi\Odm\MongoConnection;
use Pi\Odm\Mapping\Driver\AttributeDriver;
use Pi\Odm\Mapping\EntityMetaDataFactory;
use Pi\Odm\OdmConfiguration;
use Pi\Odm\Repository\RepositoryFactory;
use Pi\Common\ClassUtils;

class OdmPlugin implements IPlugin, IPreInitPlugin {

  protected $configuration;

  public function __construct(?OdmConfiguration $configuration = null)
  {
    if($configuration === null){
      $this->configuration = new OdmConfiguration();
    } else {
      $this->configuration = $configuration;
    }
    $dir = $this->configuration->getHydratorDir();
    
    /*
     * autoloader for generated files by ODM plugin like hydrators
     */
    spl_autoload_register(function($class) use($dir){
        $c = ClassUtils::getClassRealname($class);
        $myclass = $dir . '/' . $c . '.php';
        if (!is_file($myclass)) return false;
        require_once ($myclass);
    });
  }

  public function configure(IPiHost $appHost) : void
  {

    $config = $this->configuration;
    $hostConfig = $appHost->config();
    $appHost->container()->register('OdmConfiguration', function(IContainer $container) use($config, $hostConfig){
      $config->setHydratorNamespace('Mocks\\Hydrators');
      $config->setAutoGenerateHydratorClasses(true);
      $config->setDefaultDb('fitting');
      $config->setHydratorDir($hostConfig->hydratorDir());
      $config->setMetadataDriverImplementation(AttributeDriver::create(array('/home/gui/workspace/pi-framework/src/Pi/FileSystem')));
      return $config;
    });

    $appHost->container()->register('UnitWork', function(IContainer $container){
      return new UnitWork(
        $container->get('OdmConfiguration'),
        $container->get('EventManager'),
        $container->get('IEntityMetaDataFactory'),
        $container->get('MongoManager')
      );
    });

    $appHost->container()->registerAlias('Pi\Odm\UnitWork', 'UnitWork');

    $appHost->container()->register('MongoManager', function(IContainer $container){
      return new MongoManager(
        $container->get('IEntityMetaDataFactory'),
        $container->get('OdmConfiguration'),
        $container->get('DatabaseManager')
        );
    });

    $appHost->container()->register('DatabaseManager', function(IContainer $container){
      return new DatabaseManager(
          $container->get('IDbConnection'),
          $container->get('EventManager')
        );
    });

    $appHost->container()->register('RepositoryFactory', function(IContainer $container){
      return new RepositoryFactory(
        $container->get('MongoManager'),
        $container->get('EventManager')
        );
    });

    $appHost->container()->register('Pi\Odm\AbstractEntityRepair', function(IContainer $container){
      return new DocumentRepair(
        $container->get('UnitWork')
        );
    });

    $container = $appHost->container();

    $appHost->container()->register('EventManager', function(IContainer $container){
      $instance = new EventManager();
      $instance->ioc($container);
      return $instance;
    });

    $appHost->container()->register('IDbConnectionFactory', function(IContainer $container){
      $factory = new MongoConnectionFactory();
      $factory->ioc($container);
      return $factory;
    });

    $appHost->container()->register('IDbConnection', function(IContainer $container){
      $factory = $container->get('IDbConnectionFactory');
      return $factory->open();
    });

    $appHost->container()->register('IMappingDriver', function(IContainer $container){
      $instance = new AttributeDriver();
      $instance->ioc($container);
      return $instance;
    });

    $appHost->container()->register('IEntityMetaDataFactory', function(IContainer $container){

      $instance = new EntityMetaDataFactory($container->get('EventManager'), $container->get('IMappingDriver'));
      $instance->ioc($container);
      return $instance;
    });
  }
}