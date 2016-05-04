<?hh

namespace Test;

use Pi\Common\Mapping\StaticHydrator;




class StaticHydratorTest extends \PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$this->hydrator = new StaticHydrator('/tmp', 'Test\Models');
		
	}

	public function testCanGenerateHydrator() 
	{

	}
}