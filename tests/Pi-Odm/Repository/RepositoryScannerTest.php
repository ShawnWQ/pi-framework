<?hh

use Pi\Odm\Repository\RepositoryScanner;
use Pi\PiFileMapper;

class RepositoryScannerTest extends \PHPUnit_Framework_TestCase{
	
	public function testCanGetMetadataFromRepositories()
	{
		$mapper = new PiFileMapper(Set{__DIR__ . '/../../Mocks'});
		$scanner = new RepositoryScanner($mapper);
		$meta = $scanner->getMeta();
		
		$this->assertTrue($meta->contains('\Mocks\EntityRepository'));
	}
}