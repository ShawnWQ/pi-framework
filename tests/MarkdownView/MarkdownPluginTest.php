<?hh

use MarkdownView\MarkdownViewPlugin;
use Mocks\BibleHost;


class MarkdownPluginTest extends \PHPUnit_Framework_TestCase {


	public function testRegisterCreateDependencies()
	{
		$plugin = new MarkdownViewPlugin();
		$host = new BibleHost();
		$plugin->register($host);
		$this->assertNotNull($plugin->getPageResolver());
		$this->assertNotNull($plugin->getViewManager());
	}
}