<?hh

namespace Pi\Odm;

use Pi\Interfaces\IPreInitPlugin,
    Pi\Interfaces\IPlugin,
    Pi\Interfaces\IPiHost,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IContainable,
    Pi\Odm\Hydrator\MongoDBHydratorFactory,
    Pi\Odm\UnitWork,
    Pi\Odm\MongoManager,
    Pi\EventManager,
    Pi\Odm\DocumentManager,
    Pi\Odm\MongoConnection,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\Mapping\EntityMetaDataFactory,
    Pi\Odm\OdmConfiguration,
    Pi\Odm\Repository\RepositoryFactory,
    Pi\Common\ClassUtils;




class OdmPlugin implements IPlugin {

  protected $configuration;

  public function __construct(?OdmConfiguration $configuration = null) {
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

  public function register(IPiHost $appHost) : void {

    $config = $this->configuration;
    $hostConfig = $appHost->config();



    $appHost->container()->register('IMappingDriver', function(IContainer $container){
      $instance = AttributeDriver::create(array(), $container->get('Pi\EventManager'), $container->get('ICacheProvider'));
      $instance->ioc($container);
      return $instance;
    });

    $appHost->container()->register('OdmConfiguration', function(IContainer $container) use($config, $hostConfig){
      $config->setHydratorNamespace('Mocks\\Hydrators');
      $config->setAutoGenerateHydratorClasses(true);
      $config->setDefaultDb('fitting');
      $config->setHydratorDir($hostConfig->hydratorDir());
      //$config->setMetadataDriverImplementation(AttributeDriver::create(array('/home/gui/workspace/pi-framework/src/Pi/FileSystem')));
      return $config;
    });

    $appHost->container()->register(UnitWork::class, function(IContainer $container){
      return new UnitWork(
        $container->get('OdmConfiguration'),
        $container->get(EventManager::class),
        $container->get('IEntityMetaDataFactory'),
        $container->get(MongoManager::class)
      );
    });

    $appHost->container()->registerAlias('Pi\Odm\UnitWork', 'UnitWork');

    $appHost->container()->register(MongoManager::class, function(IContainer $container){
      return new MongoManager(
        $container->get('IEntityMetaDataFactory'),
        $container->get('OdmConfiguration'),
        $container->get(DatabaseManager::class)
        );
    });
    $appHost->container()->registerAlias(MongoManager::class, 'MongoManager');

    $appHost->container()->register(DatabaseManager::class, function(IContainer $container){
      return new DatabaseManager(
          $container->get('IDbConnection'),
          $container->get('Pi\EventManager')
        );
    });

    $appHost->container()->register('RepositoryFactory', function(IContainer $container){
      return new RepositoryFactory(
        $container->get(MongoManager::class),
        $container->get(EventManager::class)
        );
    });

    $appHost->container()->register('Pi\Odm\AbstractEntityRepair', function(IContainer $container){
      return new DocumentRepair(
        $container->get(UnitWork::class)
        );
    });

    $container = $appHost->container();

    /*$appHost->container()->register('EventManager', function(IContainer $container){
      $instance = new EventManager();
      $instance->ioc($container);
      return $instance;
    });*/

    $appHost->container()->register('IDbConnectionFactory', function(IContainer $container){
      $factory = new MongoConnectionFactory();
      $factory->ioc($container);
      return $factory;
    });
    $appHost->container()->registerAlias(MongoManager::class, 'MongoManager');

    $appHost->container()->register('IDbConnection', function(IContainer $container){
      $factory = $container->get('IDbConnectionFactory');
      return $factory->open();
    });

    $appHost->container()->register('IEntityMetaDataFactory', function(IContainer $container){
      $instance = new EntityMetaDataFactory($container->get(EventManager::class), $container->get('IMappingDriver'));
      $instance->ioc($container);
      return $instance;
    });
  }
}