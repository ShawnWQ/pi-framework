<?hh

namespace Pi\Memcached\Interfaces;

interface MemcachedClientsManagerInterface {
  
    public function getClient() : MemcachedClientInterface;

    private function createClient() : MemcachedClientInterface;
}
