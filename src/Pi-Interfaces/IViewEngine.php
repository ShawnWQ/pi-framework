<?hh

namespace Pi\Interfaces;

use Pi\Html\HtmlHelper;


interface IViewEngine {

	public function hasView(string $viewName, IRequest $request = null) : bool;

	public function renderPartial(string $pageName, $model, bool $renderHtml, $writter = null, HtmlHelper $helper = null);

	public function processHttpRequest(IRequest $request, IResponse $response, $dto);
}