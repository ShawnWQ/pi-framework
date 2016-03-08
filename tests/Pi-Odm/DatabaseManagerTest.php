<?hh
use Mocks\OdmContainer;
use Pi\Odm\DatabaseManager;
use Pi\Odm\MongoDatabase;
class DatabaseManagerTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$container = OdmContainer::get();
		$this->manager = $container->get('DatabaseManager');
	}
	public function testIsRegisteredInContainer()
	{
		$this->assertTrue($this->manager instanceof DatabaseManager);
	}
	public function testSelectDatabase()
	{
		$db = $this->manager->selectDatabase('random-test');
		$this->assertTrue($db instanceof MongoDatabase);
	}
}
