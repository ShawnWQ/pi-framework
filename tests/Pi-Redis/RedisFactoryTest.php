<?hh

use Pi\Redis\RedisClient;
use Pi\Redis\RedisFactory,
	Pi\EventManager,
	Pi\Cache\InMemoryCacheProvider,
	Mocks\MockMetadataFactory,
	Mocks\MockHydratorFactory,
	Mocks\MockMappingDriver;

class RedisFactoryTest extends \PHPUnit_Framework_TestCase{

	 protected EventManager $em;

	protected InMemoryCacheProvider $cache;

	protected MockMetadataFactory $metadataFactory;

	protected MockHydratorFactory $hydratorFactory;

	public function setUp() 
	{
		$this->em = new EventManager();
		$this->cache = new InMemoryCacheProvider();
		$this->metadataFactory = new MockMetadataFactory($this->em, new MockMappingDriver(array(), $this->em, $this->cache));
		$this->hydratorFactory = new MockHydratorFactory(
		  $this->metadataFactory,
		  'Mocks\\Hydrators',
		   sys_get_temp_dir()
		);
	}

	public function testRedisFactoryCanCreateClient()
	{
		$factory = new RedisFactory($this->hydratorFactory);
		$client = $factory->createClient(null);

		$this->assertTrue($client instanceof RedisClient);
	}
}
