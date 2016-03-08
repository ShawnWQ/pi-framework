<?hh
use Mocks\MongoOdmConfiguration;
use Mocks\MockOdmConfiguration;
use Mocks\OdmContainer;
use Mocks\MockEntity;
use Pi\Odm\Hydrator\MongoDBHydratorFactory;
use Pi\Odm\UnitWork;
use Pi\PiContainer;
use Pi\Interfaces\IContainer;
use Pi\EventManager;
use Pi\MongoConnection;
use Pi\Odm\Mapping\Driver\AttributeDriver;
use Pi\Odm\EntityMetaDataFactory;

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
