<?hh

namespace Pi\Odm;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class OdmMetadata {

	protected $cacheProvider;

	protected Map<string,int> $versions = Map {};

	public function ioc(IContainer $container)
	{
		$this->cacheProvider = $container->get('ICacheProvider');
		$this->versions = json_decode($this->cacheProvider->get('OdmMeta'));
	}

	public function getVersion(string $className)
	{
		return $this->versions[$className];
	}

	public function add(string $className, $factoryVersion = 0, $persist = true) 
	{
		$this->versions[$className] = $factoryVersion;
		if($persist)
			$this->cacheProvider->set('OdmMeta', json_encode($this->versions));
	}
}