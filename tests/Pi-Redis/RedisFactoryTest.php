<?hh

use Pi\Redis\RedisClient,
	Pi\Redis\RedisFactory,
	Pi\EventManager,
	Pi\Cache\InMemoryCacheProvider,
	Mocks\MockMetadataFactory,
	Mocks\MockHydratorFactory,
	Mocks\MockMappingDriver,
	Mocks\MockContainer;




class RedisFactoryTest extends \PHPUnit_Framework_TestCase{

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

	public function testRedisFactoryCanCreateClient()
	{
		$factory = new RedisFactory($this->hydratorFactory, $this->serializer);
		$client = $factory->createClient(null);

		$this->assertTrue($client instanceof RedisClient);
	}
}
