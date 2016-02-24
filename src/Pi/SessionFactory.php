<?hh

namespace Pi;

use Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\Interfaces\IClient,
    Pi\Interfaces\ISessionFactory;

class SessionFactory implements ISessionFactory {

   private $client;

   public function __construct(ICacheProvider $client)
   {
      $this->client = $client;
   }

   public function getOrCreateSession(IRequest $req, IResponse $res)
   {
     $sessionId = $req->getSessionId() ? : $res->createSessionIds($req);

     return new SessionCacheClient($this->client, $sessionId);
   }
 }
