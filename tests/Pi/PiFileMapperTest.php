<?hh
use Pi\PiFileMapper;

class PiFileMapperTest
	extends \PHPUnit_Framework_TestCase {

		private Map<string,string> $allClasses = Map{
			'\Mocks\DumbDependency' => __DIR__ . '/../Mocks/DumbDependency.php'
		};

		public function testCanMapAllFilesRecursively(){

			$ref = new PiFileMapper(Set{__DIR__ . '/../Mocks/'});
			$map = $ref->getMap();
			$this->assertTrue($map->contains('\Mocks\BibleHost'));
		}

		public function testCanMapAllFilesRecursivelyByExtension()
		{
			$ref = new PiFileMapper(Set{__DIR__ . '/../files/'});
			$map = $ref->getMapByExtension('txt');
			$this->assertTrue($map->containsKey('file-by-extension-txt.txt'));
			$this->assertTrue($map->count() === 1);
		}
}
