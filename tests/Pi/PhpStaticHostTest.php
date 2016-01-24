<?hh


use Pi\HostConfig;
use Mocks\BibleHost;
use Mocks\MockPlugin;
use Mocks\MockEntity;
use Mocks\MockEntityValidator;
use Pi\Host\HostProvider;
use Pi\IContainer;
use Pi\Container;
use Pi\Cache\LocalCacheProvider;
use Pi\Interfaces\ICacheProvider;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IHttpResponse;
use Pi\Html\HtmlHelper;

use Pi\Interfaces\IViewEngine;

class PSHViewEngine implements IViewEngine {

	public function hasView(string $viewName, IHttpRequest $request = null) : bool
	{
		return $viewName === 'test';
	}

	public function renderPartial(string $pageName, $model, bool $renderHtml, $writter = null, HtmlHelper $helper = null)
	{
		echo 'test';
	}

	public function processHttpRequest(IHttpRequest $request, IHttpResponse $response, $dto)
	{

	}
}
class PhpStaticHostTest extends \PHPUnit_Framework_TestCase{
	
	public function testCanRegisterViewEngines()
	{
		$host = new BibleHost();
		$engine = new PSHViewEngine();
		$host->registerViewEngine($engine);
	}
}