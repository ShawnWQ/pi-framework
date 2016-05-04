<?hh

namespace Pi\Memcached;

/**
 * Memcached Client Interface
 */
class MemcachedClient implements MemcachedClientInterface {
	
	/**
	 * Get the value for the given key
	 * @param string $key the key
	 */
	public function get($key)
	{

	}
	
	/**
     * Get the object for the given key
     * @param string $key the array key
     * @return ?mixed the object or null if not exists
     */
    public function getAs(string $key, string $className) : ?mixed
    {

    }

    /**
     * Set the given key value
     * If value is a object, should be accessed with getObject
     */

	public function set($key, $value)
	{

	}
	
	public function del(string $key)
	{

	}
}
