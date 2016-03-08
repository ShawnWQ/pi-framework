<?hh
use Pi\Uml\PiUmlPlugin;
use Mocks\BibleHost;

class PiUmlPluginTest extends \PHPUnit_Framework_TestCase{
	
	public function testCanRegisterInAppHost()
	{
		$_SERVER['REQUEST_URI'] = '/uml';
		$host = new BibleHost();
		$host->registerPlugin(new PiUmlPlugin());
		$host->init();
	}
}