<?hh

use Pi\Redis\RedisLocalClientManager,
	Pi\Redis\RedisFactory,
	Pi\Redis\RedisCLient,
	Pi\EventManager,
	Pi\Cache\InMemoryCacheProvider,
	Mocks\MockCnt
	Mocks\MockMetadataFactory,
	Mocks\MockHydratorFactory,
	Mocks\MockMappingDriver;

class RedisLocalClientManagerTest extends \PHPUnit_Framework_TestCase{

	protected EventManager $em;

	protected InMemoryCacheProvider $cache;

	protected MockMetadataFactory $metadataFactory;

	protected MockHydratorFactory $hydratorFactory;

	public function setUp() 
	{
		MockContainer::init();
		$this->em = MockContainer::$eventManager;
	    $this->cache = MockContainer::$cache;
	    $this->metadataFactory = MockContainer::$metadataFactory;
	    $this->hydratorFactory = MockContainer::$hydratorFactory;
	    $this->serializer = MockContainer::$serializer;
	}

	public function testGetClient()
	{
		$factory = new RedisFactory(self::$hydratorFactory);
		$manager = new RedisLocalClientManager($factory);
		$client = $manager->getClient();

		$this->assertTrue($client instanceof RedisCLient);
	}
}
