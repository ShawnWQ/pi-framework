<?hh
use Mocks\BibleHost;
use Mocks\VerseCreateRequest;
use Mocks\VerseCreateValidator;
use Pi\Interfaces\IContainer;

class CHost extends BibleHost {

	public function configure(IContainer $container) : void
	{
		parent::configure($container);
		//$this->registerValidator(new VerseCreateRequest(), new VerseCreateValidator());
	}
}
class ValidationPluginTest extends \PHPUnit_Framework_TestCase {

	public function testAssertValidationIsExecutedOnRequestFilter()
	{
		$host = new CHost();
		$_REQUEST['HTTP_METHOD'] = 'POST';
		$_REQUEST['HTTP_URI'] = '/test';
		$host->init();
	}
}