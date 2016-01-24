<?hh
use Mocks\OdmContainer;
use Mocks\MockEntity;

class EntityMetadaFactoryTest extends \PHPUnit_Framework_TestCase {

  protected $container;

  protected $factory;

  public function setUp()
  {
      $this->container = OdmContainer::get();
      $this->factory = $this->container->get('IEntityMetaDataFactory');
  }

  public function testFactoryIsRegiteredInContainer()
  {
    $this->assertNotNull($this->factory);
    $class = $this->factory->getMetadataFor(get_class(new MockEntity()));
    $this->assertNotNull($class);
  }

  public function testLoadMetaDataIntoEntityMetaDataClass()
  {

  }

  public function testCanCreateNewEntityMetaDataInstance()
  {

  }
}
