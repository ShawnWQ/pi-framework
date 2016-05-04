<?hh

namespace Pi\Memcached;

interface MemcachedFactoryInterface {
	
	public function createClient(?MemcachedConfiguration $config = null) : MemcachedClientInterface;
}