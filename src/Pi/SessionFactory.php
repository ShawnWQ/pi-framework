<?hh

namespace Pi;
use Pi\Cache\SessionCacheClient;
use Pi\Interfaces\ICachedClient;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IClient;
use Pi\Interfaces\ISessionFactory;

class SessionFactory implements ISessionFactory {

   private $client;

   public function __construct(ICachedClient $client)
   {
      $this->client = $client;
   }

   public function getOrCreateSession(IRequest $req, IResponse $res)
   {
     $sessionId = $req->getSessionId() ? : $res->createSessionIds($req);

     return new SessionCacheClient($this->client, $sessionId);
   }
 }
