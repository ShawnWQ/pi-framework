<?hh

namespace Pi;
use Pi\Auth\AuthService;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Host\BasicRequest;
use Pi\Host\BasicResponse;
use Pi\Host\HostProvider;

/*
 * Session Plugin
 * The sessions are stored in Request Items Set.
 */

 class SessionPlugin implements IPlugin {

 	const string SessionId = 'X-Pi-Id';
 	const string PermanentSessionId = 'X-Pi-Pid';
 	const string RequestItemsSessionKey = "__session";

   public static function getSessionKey(?string $sessionId)
   {
     return is_null($sessionId) ? null : IdUtils::createUrn($sessionId, 'IAuthSession');
   }

   public static function createSessionIds(IRequest $request)
   {
     if(is_null($request)) {
       $request = HostProvider::instance()->tryGet('IRequest');
     }

     $sessionId = $request->getSessionId();
     return $sessionId;
   }

   public static function createNewSession(IRequest $request, string $sessionId)
   {
     $session = AuthService::getCurrentSessionFactory();
     if(!is_null($request->userAccount())) {
      $account = $request->userAccount();
      //$session->setUserId($account->getId());
      
     }
     $session->setId($sessionId ?: self::createSessionIds($request));

     $session->onCreated($request);
     // get IAuthEvents, do onCreated

     $key = self::getSessionKey($sessionId);
     $cache = HostProvider::instance()->cacheProvider();
     $cache->set($key, json_encode($session));

     return $session;
   }

   public function configure(IPiHost $host) : void
 	{
     $host->container()->register('ISessionClient', function(IContainer $ioc) {
       $factory = new SessionFactory($ioc->get('ICachedClient'));
       $client = $factory->create();
       return $client;
     });

     $host->globalRequestFilters()->add(function(IRequest $req, IResponse $res){

       if(is_null($req->getPermanentSessionId())) {
        BasicResponse::createPermanentSessionId($res, $req);
       }

       if(is_null($req->getTemporarySessionId())) {
         BasicResponse::createTemporarySessionId($res, $req);
       }

     });
 	}

   public function pre($request)
   {
     $id = RandomString::generate(20);
     // Save in Request HEADER pi-id as a temporary cookie
   }
 }
