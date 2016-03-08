<?hh
use Mocks\OdmContainer;
use Mocks\EntityInheritedAbstract;
use Mocks\EntityInherited;
use Pi\Odm\EntityPersistent;
use Pi\Odm\EntityPersistentBuilder;


<<MappedSuperclass>>
class PTBase{

}
class PT1 extends PTBase {

}
class PersistentTest extends \PHPUnit_Framework_TestCase {

      protected $builder;

      protected $unitWork;

      protected $mongoManager;

      public function setUp()
      {
        $container = OdmContainer::get();
        $this->unitWork = $container->get('UnitWork');
        $this->mongoManager = $container->get('MongoManager');
        $this->builder = new EntityPersistentBuilder($this->mongoManager, $this->unitWork);
      }

      public function testBuilderIsCreated()
      {
        $this->assertNotNull($this->builder);
        $class = $this->mongoManager->getClassMetadata('Mocks\MockEntity');

        $persistent = new EntityPersistent($this->builder, $this->unitWork->hydratorFactory(), $this->unitWork, $this->mongoManager, $class);

      }

      public function testPrepareBuildDateInherited()
      {
        $class = $this->mongoManager->getClassMetadata('Mocks\EntityInherited');

        $persistent = new EntityPersistent($this->builder, $this->unitWork->hydratorFactory(), $this->unitWork, $this->mongoManager, $class);
        $doc = new EntityInherited();
        $doc->name('asd');
        $data = $persistent->addInsert($doc);
        $insertions = $persistent->executeInserts();

        $this->assertTrue(array_key_exists('type', $insertions));

      }
}
