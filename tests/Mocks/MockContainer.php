<?hh

namespace Mocks;

use Pi\EventManager,
	Pi\PhpSerializer,
	Pi\Cache\InMemoryCacheProvider,
	Pi\Interfaces\ICacheProvider,
	Pi\Interfaces\ISerializerService;




class MockContainer {

	static EventManager $eventManager;

	static MockMetadataFactory $metadataFactory;

	static MockMappingDriver $mappingDriver;

	static MockHydratorContainer $hydratorFactory;

	static ICacheProvider $cache;

	static ISerializerService $serializer;

	static bool $initialized;

	static function init()
	{
		self::$cache = new InMemoryCacheProvider();
		self::$serializer = new PhpSerializer();
		self::$eventManager = new EventManager();
		self::$mappingDriver = new MockMappingDriver(array(), MockContainer::$eventManager);
	    self::$metadataFactory = new MockMetadataFactory(self::$cache, MockContainer::$eventManager, self::$mappingDriver);
	    self::$hydratorFactory = new MockHydratorFactory(
	      self::$metadataFactory,
	      'Mocks\\Hydrators',
	       sys_get_temp_dir()
	    );	
	}
}