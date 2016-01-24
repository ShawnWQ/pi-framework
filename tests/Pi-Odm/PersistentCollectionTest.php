<?hh
use Mocks\OdmContainer;
use Mocks\MockEntity;
use Pi\Odm\PersistentCollection;
use Pi\Odm\UnitWork;

class PersistentCollectionTest extends \PHPUnit_Framework_TestCase {

	protected $container;

	public function setUp()
	{
		$this->container = OdmContainer::get();
	}

	public function testCreate()
	{
		$coll = new PersistentCollection(Map{}, $this->container->get('UnitWork'), $this->container->get('MongoManager'));
	}
}