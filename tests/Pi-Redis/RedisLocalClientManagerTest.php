<?hh

use Pi\Redis\RedisLocalClientManager;
use Pi\Redis\RedisFactory;
use Pi\Redis\RedisCLient,
	Pi\EventManager,
	Pi\Cache\InMemoryCacheProvider,
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
		$this->em = new EventManager();
		$this->cache = new InMemoryCacheProvider();
		$this->metadataFactory = new MockMetadataFactory($this->em, new MockMappingDriver(array(), $this->em, $this->cache));
		$this->hydratorFactory = new MockHydratorFactory(
		  $this->metadataFactory,
		  'Mocks\\Hydrators',
		   sys_get_temp_dir()
		);
	}

	public function testGetClient()
	{
		$factory = new RedisFactory($this->hydratorFactory);
		$manager = new RedisLocalClientManager($factory);
		$client = $manager->getClient();

		$this->assertTrue($client instanceof RedisCLient);
	}
}
