<?hh

namespace Test\Host;

use Pi\Host\ServiceRegistry,
	Pi\Interfaces\IPiHost,
	Pi\Interfaces\ServiceControllerInterface,
	Mocks\BibleHost,
	Mocks\BibleTestService,
	Mocks\VerseGet,
	Mocks\VerseGetResponse;




/**
 * Tests for Service Controller
 * Each ServiceController is supposed to be create once then disposed
 */
class ServiceRegisteryTest extends \PHPUnit_Framework_TestCase {
	
	protected IPiHost $host;

	protected ServiceControllerInterface $controller;

	public function setUp()
	{
		$this->host = new BibleHost();
		$this->controller = $this->host->serviceController;
		$this->host->init();
	}

	public function tearDown()
	{
		$this->host->dispose();
	}

	public function testCanInitialize()
	{
	//	$this->controller->init();
		$this->controller->throwIfNotInitialized();
	}

	public function testItDoesntThrowIfInitialized()
	{
		$throwed = false;
		try {
			$this->controller->throwIfNotInitialized();
		}
		catch(\Exception $ex) {
			$throwed = true;
		}
		$this->assertFalse($throwed);
	}

	public function testCanRegisterService()
	{
		$this->assertTrue($this->controller->hasServiceRegistered(BibleTestService::class));
	}

	public function testCanGetServiceInstance()
	{
		$instance = $this->controller->getService(BibleTestService::class);
		$this->assertNotNull($instance);
		$this->assertTrue($instance instanceof BibleTestService);
	}

	public function testCanExecute()
	{
		$req = new VerseGet();
		$this->controller->init();
		$res = $this->controller->execute($req);
		$this->assertTrue($res instanceof VerseGetResponse);
	}
}