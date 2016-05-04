<?hh

namespace Pi;

use Pi\Auth\AuthService,
    Pi\Interfaces\IPlugin,
    Pi\Interfaces\IPiHost,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\Interfaces\IContainer,
    Pi\Host\BasicRequest,
    Pi\Host\BasicResponse,
    Pi\Host\HostProvider;




/*
 * Session Plugin
 * The sessions are stored in Request Items Set.
 */

 class SessionPlugin implements IPlugin {

 	const string SessionId = 'X-Pi-Id';
 	const string PermanentSessionId = 'X-Pi-Pid';
 	const string RequestItemsSessionKey = "__session";
  const string SessionOptsPermant = 'perm';
  const string RequestItemsReturnSessionKey = "__returnsessin";

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


   //  IAuthSession
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

  public function register(IPiHost $host) : void
  {
     // Filter that sets an session id provided or create a new
    $host->requestFilters()->add(function(IRequest $req, IResponse $res) {
      return self::populateSessionFromRequest($req, $res);
    });
 	}
  
  protected static function getRequestParam(IRequest $request)
  {
    return $request->items()->get($sessionKey)
      ?: $request->cookies()->get($sessionKey)
      ?: $request->headers()->get($sessionKey)
      ?: null;
  }

  public static function returnSessionFilterRequest(IRequest $req, IResponse $res)
  {
    $param = self::getRequestParam($req);
    if($param != null) {
      $req->items()[self::RequestItemsReturnSessionKey] = true;
    }
  }

  public static function populateSessionFromRequest(IRequest $req, IResponse $res)
  {
    if(AuthService::populateFromRequestIfHasSessionId($req, $req->dto()))
        return;

      if(is_null($req->getPermanentSessionId())) {
        BasicResponse::createPermanentSessionId($res, $req);
      }

      if(is_null($req->getTemporarySessionId())) {
        BasicResponse::createTemporarySessionId($res, $req);
      }
  }

   public function pre($request)
   {
     $id = RandomString::generate(20);
     // Save in Request HEADER pi-id as a temporary cookie
   }
 }
