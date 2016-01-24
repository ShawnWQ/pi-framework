<?hh

namespace Pi\Redis;
use Pi\Common\RandomString;

/**
 * Injected manager for redis sessions
 *
 */
 abstract class RedisClientManager {

  protected $sessions = Map{};
  
  public function __construct(protected RedisFactory $factory)
  {

  }

  public abstract function getClient();

  public function getSession()
   {
     $session = new RedisClientSession();
     $id = RandomString::generate(8);
     $this->sessions[$id] = $session;
   }
 }
