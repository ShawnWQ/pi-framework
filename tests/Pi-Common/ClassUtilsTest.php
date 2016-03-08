<?hh

use Pi\Common\ClassUtils;

class MapA {
	protected string $a = 'b';

	protected string $c = 'c';
}

class MapB {
	protected $a;

	protected string $d = 'd';
	public function getA()
	{
		return $this->a;
	}
}
class ClassUtilsTest extends \PHPUnit_Framework_TestCase {

    public function testGetClassRealnameFromClassWithNamespace()
    {
      $class = 'Volupio\\Domain\\User';

      $this->assertEquals(ClassUtils::getClassRealname($class), 'User');
    }

    public function testCanMapClasses()
    {
    	$entity = new MapA();
    	$dto = new MapB();
    	$this->assertFalse($dto->getA() === 'b');
    	ClassUtils::mapDto($entity, $dto);
    	$this->assertTrue($dto->getA() === 'b');
    }
}
