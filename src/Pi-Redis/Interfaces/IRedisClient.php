<?hh

namespace Pi\Redis\Interfaces;

interface IRedisClient {
	
	public function get($key);
	public function set($key, $value);
	public function hset(string $hash, string $field, $value);
	public function hgetAll(string $hash);
	public function incr(string $key, $incryBy = 1);
	public function sadd($set, $key);
	public function smembers($set);
	public function del(string $key);
	public function srem(string $set, $key);
}
//https://github.com/ServiceStack/ServiceStack/blob/master/src/ServiceStack.Interfaces/Redis/IRedisClient.cs
