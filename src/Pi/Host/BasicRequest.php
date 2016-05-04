<?hh

namespace Pi\Host;

use Pi\Auth\AuthUserSession,
    Pi\SessionPlugin,
    Pi\Interfaces\IMessage,
    Pi\Interfaces\ICacheClient,
    Pi\Interfaces\IRequest,
    Pi\Host\HostProvider,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer,
    Pi\Interfaces\HasSessionIdInterface,
    Pi\Auth\Interfaces\IAuthSession,
    Pi\Cache\RedisCacheProvider,
    Pi\ServiceModel\AuthUserAccount,
    Pi\ServiceModel\Types\Author;




class BasicRequest implements IRequest, HasSessionIdInterface {

    protected Map<string,string> $cookies;

    /**
     * User data accessed by filters and services
     * @var [type]
     */
    protected Map<string,string> $items;

    protected $message;

    /**
     * The Request DTO after has been deserialized
     * @var [type]
     */
    
    /**
     * QueryString parameters from the Request URI
     * @var [type]
     */
    protected Map<string,string> $parameters;

    protected Map<string,string> $headers;
    
    /**
     * [$dto description]
     * @var [type]
     */
    protected $dto;

    protected $xRealIp;

    protected $requestUri;

    protected $scriptName;

    protected $physicalPath;


    protected $httpProtocol;

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

    protected $files;

    protected $hasExplicityResponseContentType;

    protected $responseType;

    /**
     * The request ContentType
     * @var [type]
     */
    protected $contentType;

    protected $isLocal;

    protected $messageFactory;

    protected $appId;

    protected $userAccount;

    protected $author;

    protected $userId;

    const contentTypeDefault = 'text\json';

    public function __construct()
    {
      $this->httpMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
      $this->requestUri = $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/'; // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"
      $this->scriptName = $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''; // <-- "/foo/index.php"

      if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'] != ''){
        $this->xRealIp = $_SERVER['REMOTE_ADDR'];
      }
    
      if(isset($_SERVER['HTTP_ORIGIN'])) {
        $this->httpOrigin = $_SERVER['HTTP_ORIGIN'];
      }

      $physicalPath = '';
      if (strpos($this->requestUri, $this->scriptName) !== false) {
          $physicalPath = $this->scriptName; // <-- Without rewriting
      } else {
          $physicalPath = str_replace('\\', '', dirname($this->scriptName)); // <-- With rewriting
      }
      $this->physicalPath = rtrim($physicalPath, '/'); // <-- Remove trailing slashes

      //Input stream (readable one time only; not available for multipart/form-data requests)
      $rawInput = @file_get_contents('php://input');
      if (!$rawInput) {
          $rawInput = '';
      }
      $this->rawInput = $rawInput;

      $this->httpProtocol = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';

      if(is_null($this->response))
        $this->response = new BasicResponse();
      
      // @fix temp fix
      if (!function_exists('apache_request_headers')) { 
        function apache_request_headers() { 
          foreach($_SERVER as $key=>$value) { 
              if (substr($key,0,5)=="HTTP_") { 
                  $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5))))); 
                  $out[$key]=$value; 
              }else{ 
                  $out[$key]=$value; 
              } 
          } 
          return $out; 
        } 
      }
      $this->serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
      $this->serverPort = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
      $this->items = Map{};
      //$this->cookies = Map{};
      $headers = apache_request_headers();
      $cookies = $_COOKIE;
      $this->headers = Map {};
      $this->cookies = Map{};
      $this->parameters = Map{};

      // Set headers
      foreach($headers as $key => $value) {
        $this->headers[$key] = $value;
      }
      // Set cookies
      foreach($cookies as $key => $value) {
        $this->cookies[$key] = $value;
      }
      // Set QueryString
      $parts = parse_url($this->requestUri);
      $this->parameters = Map {};
      if(array_key_exists('query', $parts))
      {
        parse_str($parts['query'], $p);
        if(is_array($p)) {
          foreach($p as $key => $value) {
            $this->parameters->add(Pair{$key, $value});
          }
        }
      }
      $route = HostProvider::routesManager()->get($this->requestUri);
      if($route != null && $route->params() != null) {
        foreach($route->params() as $k => $v)
        $this->parameters->add(Pair { $k, $v});
      }
    }

    public function requestUri() : string
    {
      return $this->requestUri;
    }

    public function httpOrigin() : ?string
    {
      return $this->httpOrigin;
    }

    public function isPost()
    {
      return $this->httpMethod === HttpMethod::POST;
    }

    public function isGet()
    {
      return $this->httpMethod === HttpMethod::GET;
    }

    public function isPut()
    {
      return $this->httpMethod === HttpMethod::PUT;
    }

    public function isDelete()
    {
      return $this->httpMethod === HttpMethod::DELETE;
    }

    public function isPatch()
    {
      return $this->httpMethod === HttpMethod::PATCH;
    }

    public function isOptions()
    {
        return $this->httpMethod === HttpMethod::OPTIONS;
    }

    public function isHead()
    {
      return $this->httpMethod === HttpMethod::HEAD;
    }

    public function isAjax()
    {
      if($this->parameters->contains('ajax')) {
        return true;
      } else if($this->headers->contains('X_REQUESTED_WITH') && $this->headers->get('X_REQUESTED_WITH') === 'XMLHttpRequest'){
        return true;
      } else {
        return false;
      }
    }

    public function isAuthenticated() : bool
    {
      return !is_null($this->author);
    }

    public function tryResolve(string $name)
    {
      return HostProvider::tryResolve($name);
    }

    public function queryString() : Map<string,string>
    {
      return $this->queryString;
    }


    public function isPermanentSession() : bool
    {
      return isset($this->items[SessionPlugin::SessionOptsPermant]);
    }

    public function &itemsRef() : Map
    {
      return $this->items;
    }

    public function items() : Map
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
        return array('_id' => new \MongoId(), 'displayName' => 'Mocked');
      }
      return array('_id' => $this->author->id(), 'displayName' => $this->author->displayName());
    }

    public function getUserId()
    {

        return $this->userId;
    }

    public function getSessionId() : string
    {
      // check in this request if is permanent or temporary
      $s =  $this->getTemporarySessionId();
      if(is_null($s)) {
        BasicResponse::createSessionIds($this->response(), $this);
        $s = $this->getTemporarySessionId();
      }

      return $s;
    }

    public function setSessionId(string $session) {
      $key = $this->isPermanentSession()
        ? SessionPlugin::PermanentSessionId
        : SessionPlugin::SessionId;
      $this->items[$key] = $key;
    }

    public function getSession() {
      if(isset($this->items[SessionPlugin::RequestItemsSessionKey])) {

        return $this->items[SessionPlugin::RequestItemsSessionKey];
      }

      $sessionId = $this->getSessionId();
      $sessionKey = SessionPlugin::getSessionKey($sessionId);

      $cache = $this->tryResolve('ICacheProvider');
      $session = (is_null($sessionKey) ? $cache->get($sessionKey) : null)
        ? : SessionPlugin::createNewSession($this, $sessionId);

      //$session = new AuthUserSession();
      $this->items[SessionPlugin::RequestItemsSessionKey] = $session; // @todo remove if have any

      return $session;
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

    public function addCookie(string $name, string $value, ?\DateTime $expiration = null, ?string $domain = null)
    {
      //$this->cookies[$name] = array($name, $value, $expiration, $domain);
      $this->cookies->add(Pair{$name, $value.','.$expiration.','.$domain});
    }

    public function getCookie(string $name) : ?Cookie
    {
      return array_key_exists($name, $this->cookies) ? $this->cookies[$name] : null;
    }

    public function getCookies() : array
    {
      return $this->cookies;
    }

    public function headers() : Map<string,string>
    {
      return $this->headers;
    }

    public function parameters()
    {
      return $this->parameters;
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

    public function contentType() : string
    {
      return $this->contentType;
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

    public function httpMethod() : string
    {
      return $this->httpMethod;
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

    public function saveSession(IAuthSession $session, ?\DateTime $expiresIn = null)
    {
      $this->onSaveSession($this, $session, $expiresIn);
    }

    public function onSaveSession(IRequest $httpReq, IAuthSession $session, ?\DateTime $expiresIn = null)
    {
      if($httpReq == null) return;

      $sessionId = $this->getSessionId();
      $sessionKey = SessionPlugin::getSessionKey($sessionId);
      $session = (is_null($sessionKey) ? $cache->get($sessionKey) : null)
          ? : SessionPlugin::createNewSession($this, $sessionId);
      
      $session->setLastModified(new \DateTime('now'));
      $cache = $this->tryResolve('ICacheProvider');
      $cache->set($sessionId, $session);
      if($cache instanceof RedisCacheProvider) {
        $cache->expire($sessionId, 3600);
      }
      $this->items[SessionPlugin::RequestItemsSessionKey] = $session;
    }

    public function getTemporarySessionId() : ?string
    {
      return $this->getSessionParam(SessionPlugin::SessionId);
      //return isset($this->items[SessionPlugin::SessionId])
      //? $this->items[SessionPlugin::SessionId] : null;
    }

    public function getPermanentSessionId() : ?string
    {
      return $this->getSessionParam(SessionPlugin::PermanentSessionId);
      //return isset($this->items[SessionPlugin::PermanentSessionId])
      //? $this->items[SessionPlugin::PermanentSessionId] : null;
    }

    protected function getSessionParam(string $sessionKey) : ?string 
    { 
      return $this->items->get($sessionKey)
        ?: $this->cookies->get($sessionKey)
        ?: $this->headers->get($sessionKey)
        ?: null;
    }
}
