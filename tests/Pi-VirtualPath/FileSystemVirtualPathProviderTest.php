<?hh

use Mocks\BibleHost;

use Pi\VirtualPath\FileSystemVirtualPathProvider;
use Pi\VirtualPath\FileSystemVirtualDirectory;
use Pi\VirtualPath\FileSystemVirtualFile;

class FileSystemVirtualPathProviderTest extends \PHPUnit_Framework_TestCase {
	
	protected $host;

	public function setup()
	{
		$this->host = new BibleHost();
	}

	private function createPathProvider()
	{
		return new FileSystemVirtualPathProvider($this->host, getcwd());
	}

	public function testCanCreate()
	{
		$this->assertFalse(is_null($this->createPathProvider()));
	}

	public function testGetAppHost()
	{
		$this->assertFalse(is_null($this->createPathProvider()->getAppHost()));
	}

	public function testCombineVirtualPath()
	{
		$driver = $this->createPathProvider();
		$result = $driver->combineVirtualPath('/home/jesus' , 'workspace/assets');
		$this->assertEquals($result, '/home/jesus/workspace/assets');
	}
}