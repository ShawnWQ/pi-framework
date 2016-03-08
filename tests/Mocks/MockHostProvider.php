<?hh
namespace Mocks;

use Pi\Host\BasicRequest,
	Pi\Host\HostProvider,
	Pi\Host\OperationDriver;




class MockHostProvider {
	
	public static function execute($dto)
	{
		$r = new BasicRequest();
		$r->setDto($dto);
		return HostProvider::execute($dto, $r);
	}

	public static function instance()
	{
		return HostProvider::instance();
	}

	public static function executeWarez($dto)
	{
		$host = new WarezHost();
		$host->init();
		$r = new BasicRequest();
		$r->setDto($dto);
		return HostProvider::execute($dto, $r);
	}

	/**
	 * @return string The token
	 */
	public static function authorize()
	{
		$service = HostProvider::getService('');
	    $id = new \MongoId();
	    $token = $this->service->createAuthToken($id, 'login');
	    $_REQUEST['Authorization'] = $token->getCode();
	}

	public static function createOperationDriver()
	{
		$paths = array(__DIR__ . '/../src', __DIR__ . 'Mocks');
		return OperationDriver::create($paths);
	}
}