<?hh

namespace Pi\Redis\Interfaces;

interface IRedisFactory {
	public function createClient(?RedisConfiguration $config = null) : IRedisClient;
}