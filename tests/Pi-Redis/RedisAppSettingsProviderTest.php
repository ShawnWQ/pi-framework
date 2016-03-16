<?hh

namespace Test\Redis;

use Pi\Redis\RedisClient,
	Pi\Redis\RedisAppSettingsProvider,
	Pi\Common\RandomString,
	Mocks\MongoOdmConfiguration,
    Mocks\MockOdmConfiguration,
    Mocks\OdmContainer,
    Mocks\MockEntity,
    Mocks\MockHydratorFactory,
    Mocks\MockMetadataFactory,
    Mocks\MockMappingDriver,
    Pi\PhpUnitUtils,
    Pi\Cache\InMemoryCacheProvider,
    Pi\Redis\RedisHydratorFactory,
    Pi\Odm\UnitWork,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IEntityMetaDataFactory,
    Pi\Common\ClassUtils,
    Pi\EventManager,
    Pi\MongoConnection,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\EntityMetaDataFactory;;




class RedisAppSettingsProviderTest extends \PHPUnit_Framework_TestCase {
	
	protected $provider;

	protected $redis;

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
		$this->redis = new RedisClient($this->hydratorFactory);
		$this->provider = new RedisAppSettingsProvider($this->redis);
	}

	public function testCanGetSetList()
	{
		$set = Set{};
		$set->add(RandomString::generate())
			->add(RandomString::generate())
			->add(RandomString::generate());
		$key = RandomString::generate();
		$this->provider->set($key, RandomString::generate());
		$setCache = $this->provider->getList($key);
		$this->assertEquals($setCache->count(), $set->count());
	}
}