<?hh

namespace Pi\Interfaces;

use Pi\Html\HtmlHelper;


interface IViewEngine {

	public function hasView(string $viewName, IHttpRequest $request = null) : bool;

	public function renderPartial(string $pageName, $model, bool $renderHtml, $writter = null, HtmlHelper $helper = null);

	public function processHttpRequest(IHttpRequest $request, IHttpResponse $response, $dto);
}