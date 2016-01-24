<?hh

use Mocks\BibleHost;
use Pi\Host\HostProvider;


class HostProviderTest extends \PHPUnit_Framework_TestCase {
	
	public function setUp()
	{

	}

	public function testRan()
	{
		$host = new BibleHost();
		HostProvider::configure($host);
		HostProvider::plugins()->add('1');
		
		HostProvider::plugins()->add('1asd');
	}
}