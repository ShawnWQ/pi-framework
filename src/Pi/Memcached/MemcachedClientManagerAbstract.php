<?hh

namespace Pi\Memcached;

use Pi\Memcached\Interfaces\MemcachedFactoryInterface,
    Pi\Memcached\Interfaces\MemcachedClientInterface;

/**
 * Injected manager for redis sessions
 *
 */
 abstract class MemcachedClientManagerAbstract {

  
  
  public function __construct(protected MemcachedFactoryInterface $factory)
  {

  }

  public abstract function getClient() : MemcachedClientInterface;

 }
