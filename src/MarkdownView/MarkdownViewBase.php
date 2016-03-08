<?hh

namespace MarkdownView;

class MarkdownViewBase extends ViewBase {
	
	public function __construct(
		IAppHost $host,
		HtmlHelper $htmlHelper,
		Set $scope,
		bool $renderHtml, /** * Flag to render Markdown (defaul) or html */
		)
	{
		parent::__construct($host, $htmlHelper, $scope);
	}
	
}