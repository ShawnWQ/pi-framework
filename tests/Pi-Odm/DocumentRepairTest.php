<?hh

use Mocks\OdmContainer;

class DocumentRepairTest extends \PHPUnit_Framework_TestCase {
	
	protected $ioc;

	public function setUp()
	{
		$this->ioc = OdmContainer::get();
		
	}

	public function testImplementationIsRegisteredAtIoc()
	{
		$this->assertFalse(is_null($this->ioc->get('Pi\Odm\AbstractEntityRepair')));
	}
}