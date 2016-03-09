<?hh
use Pi\Redis\RedisClient,
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
    Pi\PiContainer,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IEntityMetaDataFactory,
    Pi\Common\ClassUtils,
    Pi\EventManager,
    Pi\MongoConnection,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\EntityMetaDataFactory;;

class RedisClientTest extends \PHPUnit_Framework_TestCase {

	protected $client;

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
		$this->client = new RedisClient($this->hydratorFactory);
    $this->client->connect();
	}

  public function testAssertCreatesAnRedisInstance()
  {
  	$this->assertTrue($this->client instanceof \Redis);
  }

  public function testCanSetAndObject()
  {
    $entity = new MockEntity();
    $entity->name('now-set');
    $key = RandomString::generate();
    $this->client->set($key, $entity);
    $obj = $this->client->getAs($key, get_class($entity));
    $this->assertTrue($obj instanceof MockEntity);
    $this->assertEquals($obj->name(), 'now-set');
  }

  public function testCanSetAndGetHydratedObjectAndExpired()
  {
    $entity = new MockEntity();
    $entity->name('now-set');
    $key = RandomString::generate();
    $this->client->set($key, $entity);
    $this->client->expire($key, 2);
    $this->client->getAs($key, get_class($entity));
    sleep(5);
    $this->assertNull($this->client->getAs($key, get_class($entity)));
  }

  public function testCanSetandGetString()
  {
  	$key = RandomString::generate();
  	$this->client->set($key, 'abc');
  	$this->assertEquals('abc', $this->client->get($key));
  }

  public function testCanCountList()
  {
    $key = RandomString::generate();
    $count = $this->client->llen($key);
    $this->assertEquals($count, 0);
    $this->client->rpush($key, 'now');
    $this->client->rpush($key, 'before');
    $count = $this->client->llen($key);
    $this->assertEquals($count, 2);
  }

  public function testPushListAndGetRange()
  {
    $key = RandomString::generate();
    $this->client->lpush($key, 'new-value');
    $this->assertEquals(count($this->client->lrange($key, 0, -1)), 1);
  }

  public function testDelete()
  {
    $key = RandomString::generate();
    $this->client->set($key, '1');
    $this->assertTrue(is_string($this->client->get($key)));
    $this->client->delete($key);
    $this->assertFalse(is_string($this->client->get($key)));
  }
}
