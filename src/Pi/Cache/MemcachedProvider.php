<?hh

namespace Pi\Cache;

use Pi\Interfaces\ICacheProvider,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer,
	Pi\Host\HostProvider,
	Pi\Common\RandomString;




/**
 * Cache implementation for Memcached
 */
class MemcachedProvider implements ICacheProvider, IContainable {

	protected \Memcached $mem;

	public function __construct(Map<string,int> $instances)
	{
		$this->mem = new \Memcached();
		foreach ($instances as $port => $host) {
			$this->mem->addServer($host, $port);
		}

	}
	
	public function ioc(IContainer $container) {}

  	public function get($key = null)
  	{
  		return $this->mem->get($key);
  	}
	
	public function set($key, $value)
	{
		$this->mem->set($key, $value);
	}

	public function contains($key)
  	{
  		return $this->mem->get($key) != null;
  	}
}