<?hh

use Mocks\MongoOdmConfiguration,
    Mocks\MockOdmConfiguration,
    Mocks\OdmContainer,
    Mocks\MockEntity,
    Pi\Redis\RedisHydratorFactory,
    Pi\Odm\UnitWork,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\IEntityMetaDataFactory,
    Pi\EventManager,
    Pi\MongoConnection,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\EntityMetaDataFactory;




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
