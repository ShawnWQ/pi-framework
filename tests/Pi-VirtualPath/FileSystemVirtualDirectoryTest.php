<?hh

use Mocks\BibleHost;
use Pi\PhpUnitUtils;
use Pi\VirtualPath\FileSystemVirtualPathProvider;
use Pi\VirtualPath\FileSystemVirtualDirectory;
use Pi\VirtualPath\FileSystemVirtualFile;

class FileSystemVirtualDirectoryTest extends \PHPUnit_Framework_TestCase {
	
	protected $provider;

	public function setup()
	{
		$this->provider = new FileSystemVirtualPathProvider(new BibleHost(), getcwd());
	}


	public function testCanGetFilesInternally()
	{
		$files = PhpUnitUtils::callMethod(
				$this->provider->getRootDirectory(),
				'getFilesInt',
				array(getcwd() . '/tests/Pi-VirtualPath/test-dir')
			);
		$this->assertTrue(is_array($files) && count($files) > 1);
	}

}