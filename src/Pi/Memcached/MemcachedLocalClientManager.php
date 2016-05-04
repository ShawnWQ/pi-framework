<?hh

namespace Pi\Memcached;

use Pi\Memcached\Interfaces\MemcachedClientsManagerInterface,
    Pi\Memcached\Interfaces\MemcachedClientInterface,
    Pi\Memcached\Interfaces\MemcachedFactoryInterface;




class MemcachedLocalClientManager extends MemcachedClientManagerAbract {

  public function __construct(protected MemcachedFactoryInterface $factory)
  {
    parent::__construct($factory);
  }

  public function getClient() : MemcachedClientInterface
  {
    return $this->createClient();
  }

  private function createClient() : MemcachedClientInterface
  {
    return $this->factory->createClient();
  }
}
