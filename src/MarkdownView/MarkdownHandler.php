<?hh //strict

/**
 * Handler to serve markdown pages
 */

namespace MarkdownView;


use Pi\Host\Handlers\AbstractPiHandler;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IViewEngine;
use Pi\Interfaces\IAppHost;
use Pi\Host\HostProvider;
use Pi\PiHost;
use Pi\Html\HtmlHelper;



class MarkdownHandler extends AbstractPiHandler implements IViewEngine {

	protected MarkdownViewPlugin $format;

	protected MarkdownPage $page;

	protected $model;

	const VIEW_KEY = 'View';

	protected string $pathInfo;

	public function __construct(
		)
	{
		parent::__construct();
		$host = HostProvider::instance();
		try {
			$this->init($host);
		} catch(\Exception $ex) {
			throw new \Exception('MarkdownHandler: %s', print_r($ex));
		}
	}

	public static function canHandle(string $path) : bool
	{
		return in_array(pathinfo($path, PATHINFO_EXTENSION), array('html', 'md', 'htm', 'php'));
	}

	public function catchAllHandler(string $httpMethod, string $pathInfo, string $filePath)
	{
		if(!self::canHandle($pathInfo)) {

		}
	}

	public function init(PiHost $host)
	{

	}

	protected function findPage(IRequest $request, $model)
	{

	}

	public function hasView(string $viewName, IRequest $request = null) : bool
	{
		$view = !array_key_exists(self::VIEW_KEY, $request->items()) ?: $request->items()[self::VIEW_KEY];
	}

	public function renderPartial(string $pageName, $model, bool $renderHtml, $writter = null, HtmlHelper $helper = null)
	{

	}

	public function processHttpRequest(IRequest $request, IResponse $response, $dto)
	{

	}

	public function createRequest(IRequest $request, string $operationName)
	{

		$requestType = getOperationType($operationName);
	}

	public function createResponse(IRequest $request, $requestDto)
	{

	}
}
