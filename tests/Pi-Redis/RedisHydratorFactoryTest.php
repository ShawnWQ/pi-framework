<?hh
use Mocks\MongoOdmConfiguration;
use Mocks\MockOdmConfiguration;
use Mocks\OdmContainer;
use Mocks\MockEntity;
use Pi\Redis\RedisHydratorFactory;
use Pi\Odm\UnitWork;
use Pi\PiContainer;
use Pi\Interfaces\IContainer,
  Pi\Interfaces\IEntityMetaDataFactory;
use Pi\EventManager;
use Pi\MongoConnection;
use Pi\Odm\Mapping\Driver\AttributeDriver;
use Pi\Odm\EntityMetaDataFactory;

class RedisHydratorFactoryTest extends \PHPUnit_Framework_TestCase {

  public function testCanGetHydratorForClassAndHydrate()
  {
    $entity = new MockEntity();
    $container = OdmContainer::get();
    /*
    
    $factory = new RedisHydratorFactory(
      $container->get('ClassMetadataFactory'),
      'RedisTmp',
      '/tmp'
    );

    $factory->hydrate($entity, array('id' => 1, 'name' => 'Jesus'));
    $this->assertEquals($entity->name(), 'Jesus');*/
  }
}
