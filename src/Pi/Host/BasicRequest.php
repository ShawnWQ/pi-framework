<?hh

namespace Pi\Host;

use Pi\Auth\AuthUserSession;
use Pi\SessionPlugin;
use Pi\Interfaces\IMessage;
use Pi\Interfaces\ICacheClient ;
use Pi\Interfaces\IRequest;
use Pi\Host\HostProvider;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\ServiceModel\AuthUserAccount;
use Pi\ServiceModel\Types\Author;

class BasicRequest implements IRequest {

    protected $items;

    protected $message;

    protected $dto;

    protected $originalResponse;

    protected $response;

    protected $serverName;

    protected $serverPort;

    protected $operationName;

    protected $requestPreferences;

    protected $inputStream;

    /**
     * @var array
     */
    protected $acceptTypes;

    protected $remoteIp;

    protected $isSecureConnection;

    protected $queryString; // named value

    protected $headers;

    protected $files;

    protected $hasExplicityResponseContentType;

    protected $responseType;

    protected $contentType;

    protected $isLocal;

    protected $messageFactory;

    protected $appId;

    protected $userAccount;

    protected $author;

    protected $userId;

    public function __construct()
    {
      $this->response = new BasicResponse();
      $this->serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
      $this->serverPort = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
      $this->items = array();
    }

    public function isAuthenticated() : bool
    {
      return !is_null($this->author);
    }

    public function tryResolve(string $name)
    {
      return HostProvider::tryResolve($name);
    }

    public function getTemporarySessionId() : ?string
    {
      return isset($this->items[SessionPlugin::SessionId])
      ? $this->items[SessionPlugin::SessionId] : null;
    }

    public function getPermanentSessionId() : ?string
    {
      return isset($this->items[SessionPlugin::PermanentSessionId])
      ? $this->items[SessionPlugin::PermanentSessionId] : null;
    }

    public function &itemsRef() : array
    {
      return $this->items;
    }

    public function items() : array
    {
      return $this->items;
    }

    public function setUserAccount(AuthUserAccount $dto)
    {

      $this->userAccount = $dto;
      $this->author = new Author();
      $this->author->setId($dto->userId());
      $this->userId = $dto->userId();
      $this->author->setDisplayName($dto->name());
    }

    public function userAccount()
    {
      return $this->userAccount;
    }

    public function getAuthor() : Author
    {
      return $this->author;
    }

    public function author()
    {
      if(is_null($this->author)) {
        throw new \Exception('not authorized');
      }
      return array('_id' => $this->author->id(), 'displayName' => $this->author->displayName());
    }

    public function getUserId()
    {

        return $this->userId;
    }

    public function getSessionId()
    {
      // check in this request if is permanent or temporary
      $s =  $this->getTemporarySessionId();
      if(is_null($s)) {
        BasicResponse::createSessionIds($this->response(), $this);
        $s = $this->getTemporarySessionId();
      }

      return $s;
    }

    public function getSession()
    {
      if(isset($this->items[SessionPlugin::RequestItemsSessionKey])) {

        return $this->items[SessionPlugin::RequestItemsSessionKey];
      }

      $sessionId = $this->getSessionId();
      $sessionKey = SessionPlugin::getSessionKey($sessionId);

      $cache = $this->tryResolve('ICacheProvider');
      $session = (is_null($sessionKey) ? $cache->get($sessionKey) : null)
        ? : SessionPlugin::createNewSession($this, $sessionId);

      //$session = new AuthUserSession();
      $this->items[SessionPlugin::RequestItemsSessionKey] = $session;

      return $session;
      /*
      if (httpReq == null) return null;

            object oSession = null;
            if (!reload)
                httpReq.Items.TryGetValue(SessionFeature.RequestItemsSessionKey, out oSession);

            if (oSession != null)
                return (IAuthSession)oSession;

            using (var cache = httpReq.GetCacheClient())
            {
                var sessionId = httpReq.GetSessionId();
                var sessionKey = SessionFeature.GetSessionKey(sessionId);
                var session = (sessionKey != null ? cache.Get<IAuthSession>(sessionKey) : null)
                    ?? SessionFeature.CreateNewSession(httpReq, sessionId);

                if (httpReq.Items.ContainsKey(SessionFeature.RequestItemsSessionKey))
                    httpReq.Items.Remove(SessionFeature.RequestItemsSessionKey);

                httpReq.Items.Add(SessionFeature.RequestItemsSessionKey, session);
                return session;
            }
            */
    }

    public function ioc(IContainer $ioc)
    {

    }

    public function setResponse($response)
    {
      $this->response = $response;
    }

    public function response()
    {
      return $this->response;
    }
    public function resolve($name)
    {
      $container = HostProvider::instance();
      $this->messageFactory = $container->tryResolve('IMessageFactory');
      return $container->tryResolve($name);
    }

    public function verbe() : string
    {
      return 'GET';
    }

    public function preferences()
    {
      throw new \Pi\NotImplementedException();
    }

    public function setAppId($value)
    {
      $this->appId = $value;
    }

    public function appId()
    {
      return $this->appId;
    }

    public function dto()
    {
      return $this->dto;
    }

    public function getRawBody()
    {
      throw new \Pi\NotImplementedException();
    }

    public function remoteIp()
    {
      throw new \Pi\NotImplementedException();
    }

    public function inputStream()
    {
      throw new \Pi\NotImplementedException();
    }

    public function contentLong()
    {
      throw new \Pi\NotImplementedException();
    }
    public function files()
    {
      throw new \Pi\NotImplementedException();
    }

    public function urlReferrer()
    {
      throw new \Pi\NotImplementedException();
    }

    public function setDto($dto){
      $this->dto = $dto;
    }

    public function httpMethodAsApplyTo()
    {
      throw new \Pi\NotImplementedException();
    }

    public function operationName()
    {
      if(is_null($this->operationName))
      {
        if(is_null($this->message)) {
          return null;
        }
        $this->operationName = $this->message->body()->getType()->getOperationName();
      }

      return $this->operationName;
    }

    public function requestPreferences()
    {
      if(is_null($this->requestPreferences))
      {
        $this->requestPreferences = new RequestPreferences($this);
      }

      return $this->requestPreferences;
    }

    public function serverName() : string
  {
    return $this->serverName;
  }

  public function serverPort() : int
  {
    return $this->serverPort;
  }
}
