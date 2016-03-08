<?hh

namespace MarkdownView;

use MarkdownView\Interfaces\IMarkdownConfig;
use Pi\VirtualPath\Interfaces\IVirtualPathProvider;

class MarkdownViewManager {
	
	protected array $pages = array();

	protected Map<string,string> $viewsNamesMap = Map{};

	
	public function __construct(
		protected IVirtualPathProvider $pathProvider,
		protected IMarkdownConfig $config
		)
	{

	}

	public function init()
	{

	}

	protected function getFile(string $path) : IVirtualFile
	{
		$relative = $path;  //hack
		$file = $this->pathProvider->getFile($relative);
		return $file;
	}

	public function addPage(string $filePath) : MarkdownPage
	{
		$file = $this->getFile($filePath);
		return $this->addPageMarkdown($page);
	}

	public function addPageMarkdown(MarkdownPage $page)
	{
		$pagePath = $this->getPagePath($page);
	}

	private function getPagePath(MarkdownPage $page)
	{
		
	}

	public function getPageByPathInfo(string $pathInfo) : ?MarkdownPage
	{

	}

	public function getPageByName(string $name)
	{
		
	}
}