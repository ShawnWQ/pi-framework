<?hh

namespace Pi\Redis\Interfaces;

/**
 * Redis Client Interface
 */
interface IRedisClient {
	
	/**
	 * Get the value for the given key
	 * @param string $key the key
	 */
	public function get($key);
	
	/**
     * Get the object for the given key
     * @param string $key the array key
     * @return ?mixed the object or null if not exists
     */
    public function getAs(string $key, string $className) : ?mixed;

    /**
     * Set the given key value
     * If value is a object, should be accessed with getObject
     */

	public function set($key, $value);
	public function hset(string $hash, string $field, $value);
	public function hgetAll(string $hash);
	/**
	 * Increments the number stored at key by one or given $incrBy. 
	 * If the key does not exist, it is set to 0 before performing the operation.
	 * @param string $key the key 
	 * @param int $incrBy the value to be incremented
	 */ 
	public function incr(string $key, $incrBy = 1);
	public function sadd($set, $key);
	public function smembers($set);
	public function del(string $key);
	public function srem(string $set, $key);
}
//https://github.com/ServiceStack/ServiceStack/blob/master/src/ServiceStack.Interfaces/Redis/IRedisClient.cs
