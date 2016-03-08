<?hh


namespace Pi\Redis;


use Pi\AbstractAppSettingsProvider,
	Pi\Interfaces\AppSettingsProviderInterface,
	Pi\Interfaces\IContainable,
	Pi\Redis\Interfaces\IRedisClient;




class RedisAppSettingsProvider extends AbstractAppSettingsProvider implements AppSettingsProviderInterface, IContainable {
	
	const REDIS_SET = 'pi::appsettings';

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

	public function getString(string $name) : ?string
	{
		return $this->redis->hget(self::REDIS_SET, $name) ?: null;
	}

	public function getList(string $key) : Set<string>
	{
		$set = $this->redis->hget(self::REDIS_SET, $key);
		return unserialize($set) ?: new Set();
	}

	public function set(string $key, mixed $value) : void
	{
		if(is_string($value)) {
			$this->redis->hset(self::REDIS_SET, $key, $value);
		} else if(is_scalar($value)) {
			$this->redis->hset(self::REDIS_SET, $key, serialize($value));
		} else if(is_object($value) && $value instanceof \JsonSerializable) {
			$this->redis->hset(self::REDIS_SET, $key, json_encode($value));
		} else {
			throw new \Exception('AppSettingsProvider only allow string, scalar or objects implementing \JsonSerializable ');
		}
	}

	public function addToList(string $key, string $value)
	{
		$list = $this>getList($key);
		$list->add($value);
		$this->set($key, $list);
	}

	public function setString(string $name, string $value) : void	
	{
		$this->redis->set($name, $value);
	}
}