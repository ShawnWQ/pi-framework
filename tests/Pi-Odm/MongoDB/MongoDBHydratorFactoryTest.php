<?hh

use Mocks\MongoOdmConfiguration,
    Mocks\MockOdmConfiguration,
    Mocks\OdmContainer,
    Mocks\MockEntity,
    Pi\Odm\Hydrator\MongoDBHydratorFactory,
    Pi\Odm\UnitWork,
    Pi\Interfaces\IContainer,
    Pi\EventManager,
    Pi\MongoConnection,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\EntityMetaDataFactory;




class MongoDBHydratorFactoryTest extends \PHPUnit_Framework_TestCase {

  public function testCanGetHydratorForClassAndHydrate()
  {
    $entity = new MockEntity();
    $container = OdmContainer::get();
    $unitWork = $container->get('UnitWork');

    $factory = new MongoDBHydratorFactory(
      $container->get('OdmConfiguration'),
      $container->get('IEntityMetaDataFactory'),
      $container->get('EventManager'),
      $container->get('UnitWork')
    );

    $factory->hydrate($entity, array('id' => 1, 'name' => 'Jesus'));
    $this->assertEquals($entity->name(), 'Jesus');
  }
}
