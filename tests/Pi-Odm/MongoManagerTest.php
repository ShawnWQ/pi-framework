<?hh
use Pi\Odm\MongoManager;
use Mocks\OdmContainer;
use Mocks\ADT1;

class MongoManagerTest extends \PHPUnit_Framework_TestCase{

	protected $manager;

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->manager = $container->get('MongoManager');
	}

	public function testIsRegisteredInContainer()
	{
		$this->assertTrue($this->manager instanceof MongoManager);
	}

	public function testCanResolveInheritanceTypes()
	{
		$class = new ADT1();
		$col = $this->manager->getDocumentCollection(get_class($class));
		$this->assertTrue(!is_null($col));
		$this->assertEquals($col->getMongoCollection()->getName(), 'adt-base');
	}
}
