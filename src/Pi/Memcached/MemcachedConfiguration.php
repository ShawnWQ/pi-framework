<?hh

namespace Pi\Memcached;

class MemcachedConfiguration {
	
	public function __construct(
		protected Map<string,int> $instances
	)
	{

	}

	public static function fromArray(array $array) : MemcachedConfiguration
	{
		return new self(
			array_key_exists('instances', $array) && is_array($array['instances']) 
			? new Map($array['instances']) 
			: Map{});
	}

	public function instances() : Map<string,string>
	{
		return $this->instances;
	}
}