<?hh

namespace MarkdownView;


use Pi\Interfaces\IAppHost;

class ViewBase {
	
	public function __construct(
		protected IAppHost $host,
		protected HtmlHelper $htmlHelper,
		protected Set $scope
		)
	{

	}

}