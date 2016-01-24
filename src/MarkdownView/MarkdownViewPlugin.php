<?hh

namespace MarkdownView;


use Pi\Interfaces\IPiHost;
use MarkdownView\Interfaces\IMarkdownConfig;
use Pi\Interfaces\IContainer;
use Pi\Host\HostProvider;
use Pi\VirtualPath\FileSystemVirtualPathProvider;
use Pi\Host\AbstractPiHandler;


class MarkdownViewPlugin  {
	
	protected $instance;

	protected string $fileExt;

	protected string $defaultPage;

	protected string $webhostUrl;

	protected $viewManager;

	protected $pageResolver;

	protected $virtualPathProvider;

	protected MarkdownConfig $config;

	public function __construct($config = null)
	{
		$this->config = !is_null($config) ?: new MarkdownConfig();
		$this->fileExt = '.html';
		$this->defaultPage = 'index.html';
	}

	public function register($host) : void
	{
		if(!$host instanceof IPiHost) {
			throw new \Exception('appHost');
		}
		

		try {
			$this->bindToAppHost($host);
			$this->init();
			
		}
		catch(\Exception $ex) {
			throw $ex;
		}
		
	}

	public function init() : void
	{
		if(!is_null($this->instance)) {
			// log ja foi iniciado	
			if(!is_null($this->viewManager) && !is_null($this->pageResolver)) {
				return $this;
			}
			// log imcompleto
		}

		$this->instance = $this;
		$this->viewManager = $this->createViewManager();
		$this->pageResolver = $this->createPageResolver();
	}

	public function getViewManager() : MarkdownViewManager
	{
		if(is_null($this->viewManager)) 
			throw new \Exception('MarkdownViewPlugin wasnt registered');
		
		return $this->viewManager;
	}

	public function getPageResolver() : MarkdownHandler
	{
		if(is_null($this->pageResolver)) 
			throw new \Exception('MarkdownPagePlugin wasnt registered');

		return $this->pageResolver;
	}

	public function findPathInfo(string $pathInfo) : ?MarkdownPage
	{
		return $this->viewManager->getPageByPathInfo($pathInfo);
	}

	private function createViewManager() : MarkdownViewManager
	{
		return new MarkdownViewManager($this->virtualPathProvider, $this->config);
	}

	private function createPageResolver() : MarkdownHandler
	{
		return new MarkdownHandler($this, $this->viewManager);
	}

	protected function bindToAppHost(IPiHost $host) : void
	{
		$host->container()->register('Pi\VirtualPath\Interfaces\IVirtualPathProvider', function(IContainer $ioc){
			return new FileSystemVirtualPathProvider(HostProvider::instance(), '../../');
		});
		$this->virtualPathProvider = $host->container()->get('Pi\VirtualPath\Interfaces\IVirtualPathProvider');
		$this->webhostUrl = $this->webhostUrl ?: $host->config()->domain();
	}
	
}