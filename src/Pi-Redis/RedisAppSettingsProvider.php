<?hh


namespace Pi\Redis;


use Pi\AbstractAppSettingsProvider,
	Pi\Interfaces\AppSettingsProviderInterface,
	Pi\Interfaces\IContainable,
	Pi\Redis\Interfaces\IRedisClient;




class RedisAppSettingsProvider extends AbstractAppSettingsProvider implements AppSettingsProviderInterface, IContainable {
	
	public function __construct(
		protected IRedisClient $redis)
	{

	}
	public function getAll() : Map<string,string>
	{
		throw new \Exception('Not Implemented');
	}

	public function getAllKeys() : Set<string>
	{
		throw new \Exception('Not Implemented');
	}

	public function exists(string $key) : bool
	{
		return $this->redis->get($key) != null;
	}

	public function getString(string $name) : string
	{
		return $this->redis->get($name);
	}

	public function getList(string $key) : Set<string>
	{
		throw new \Exception('Not Implemented');
	}

	public function getMap(string $key) : Map<string,string>
	{	
		throw new \Exception('Not Implemented');
	}

	public function setString(string $name, string $value) : void	
	{
		$this->redis->set($name, $value);
	}
}