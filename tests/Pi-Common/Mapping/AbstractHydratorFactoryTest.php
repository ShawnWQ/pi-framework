<?hh

use Mocks\MongoOdmConfiguration,
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
    Pi\Odm\EntityMetaDataFactory;




class AbstractHydratorFactoryTest extends \PHPUnit_Framework_TestCase {
  
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

  public function testCanGetHydrateArrayToObject()
  {
    $entity = new MockEntity();
    $entity->name('Jesus');
    $entity->id(1);
    $this->hydratorFactory->hydrate($entity, array('id' => 1, 'name' => 'Jesus'));
    $this->assertEquals($entity->name(), 'Jesus');
  }

  public function testCanGenerateHydratorCacheAndReuse() 
  {
    $entity = new MockEntity();
    $className = get_class($entity);
    $classMetaData = $this->hydratorFactory->getMetadataFor($className);
    $fileName = $this->hydratorFactory->getClassFileName($className);
    $hydratorClass = $this->hydratorFactory->getHydratorClassName($className);

    if(PhpUnitUtils::callMethod($this->hydratorFactory, 'hydratorFileExists', array($className))) {
      PhpUnitUtils::callMethod($this->hydratorFactory, 'removeHydratorFile', array($className));
    }

    $this->assertFalse(PhpUnitUtils::callMethod($this->hydratorFactory, 'hydratorFileExists', array($className)));
    $this->assertFalse($this->hydratorFactory->hydrators()->contains($className));

    $hydrator = $this->hydratorFactory->getHydrator($className);
    $this->assertTrue(PhpUnitUtils::callMethod($this->hydratorFactory, 'hydratorFileExists', array($className)));
    $this->assertTrue($this->hydratorFactory->hydrators()->contains($className));

    $hydrator->hydrate(array('name' => 'mock-hydrator'), $entity);
    $this->assertEquals($entity->name(), 'mock-hydrator');
  }

  public function testHydratorCanExtract()
  {
    $entity = new MockEntity();
    $className = get_class($entity);
    $classMetaData = $this->hydratorFactory->getMetadataFor($className);

    $hydrator = $this->hydratorFactory->getHydrator($className);
    $entity->name('can-extract');
    $extract = $hydrator->extract($entity);
    $this->assertEquals($entity->name(), $extract['name']);
  }

  public function testHydratorCanGetNewInstanceOfClass()
  {
    $entity = new MockEntity();
    $className = get_class($entity);
    $class = $this->hydratorFactory->getInstanceOf($className, array('name' => 'new-instance'));
    $this->assertTrue($class instanceof $entity);
    $this->assertEquals($class->name(), 'new-instance');
  }
}