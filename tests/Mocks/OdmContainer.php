<?hh

namespace Mocks;

use Mocks\MongoOdmConfiguration,
	Mocks\MockOdmConfiguration,
	Mocks\MockEntity,
	Mocks\EntityRepository,
	Pi\Odm\Hydrator\MongoDBHydratorFactory,
	Pi\Odm\UnitWork,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\IPiHost,
	Pi\EventManager,
	Pi\Odm\MongoConnection,
	Pi\Odm\Mapping\Driver\AttributeDriver,
	Pi\Odm\Mapping\EntityMetaDataFactory,
	Mocks\BibleHost;





class OdmContainer {
  
  static IPiHost $instance;

  public static function get()
  {
  	if(is_null(self::$instance)) {
  		self::$instance = new BibleHost();
  		self::$instance->init();
  	}

    self::$instance->container()->registerRepository(MockEntity::class, EntityRepository::class);
    return self::$instance->container();
  }

  public static function dispose()
  {
  	self::$instance->dispose();
  }
}
