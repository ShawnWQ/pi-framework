<?hh

namespace Pi;

/**
 * The Route is managed by the ServicecRoute
 * The routes have the necessary information to associate an Uri to a Request type
 * They arent needed for message queue services resolve the operation
 */
class Route implements \JsonSerializable{
  /**
   * The callable function for service method
   */
  protected $callable;

  /**
   * Arguments to be invoked with callable
   */
  protected $args;

  /**
   * Parameters from url pattern
   */
  public $params;

  protected $conditions;

  protected static $defaultConditions = array();

  protected $paramNames = array();

  protected $paramNamesPath;

  protected $perVerb = array();

  public function __construct(
    protected $pattern,
    protected $serviceType,
    protected $requestType,
    protected $caseSensitive = false,
    protected array $verbs = null)
  {
    if(!is_null($verbs) && is_array($verbs)){
      foreach($verbs as $verb){
        $this->perVerb[strtoupper($verb)] = $pattern;
      }
      
    }
    else {
      $this->fverbs = array('GET');
    }

    $this->setConditions(self::getDefaultConditions());
    $this->params = array();

  }

  public function jsonSerialize()
  {
    return get_object_vars($this);
  }
  public static function getDefaultConditions()
  {
      return self::$defaultConditions;
  }
  public function setConditions(array $conditions)
  {
      $this->conditions = $conditions;
  }

  public function setPattern($pattern)
  {
      $this->pattern = $pattern;
  }

  /**
   * The Route pattern
   * @return string|null The Route pattern
   */
  public function pattern() : ?string
  {
    return $this->pattern;
  }

  public function serviceType()
  {
    return $this->serviceType;
  }

  public function requestType(){
    return $this->requestType;
  }

  public function params()
  {
    return $this->params;
  }

  public function paramNames()
  {
    return $this->paramNames;
  }

  /**
   * Convert a URL parameter (e.g. ":id", ":id+") into a regular expression
   * @param  array $m URL parameters
   * @return string       Regular expression for URL parameter
   */
  protected function matchesCallback($m)
  {
    
      $this->paramNames[] = $m[1];
      if (isset($this->conditions[$m[1]])) {
          return '(?P<' . $m[1] . '>' . $this->conditions[$m[1]] . ')';
      }
      if (substr($m[0], -1) === '+') {
          $this->paramNamesPath[$m[1]] = 1;

          return '(?P<' . $m[1] . '>.+)';
      }

      return '(?P<' . $m[1] . '>[^/]+)';
  }

  /**
   * Matches URI?
   *
   * Parse this route's pattern, and then compare it to an HTTP resource URI
   * This method was modeled after the techniques demonstrated by Dan Sosedoff at:
   *
   * http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
   * https://gist.github.com/zeuxisoo/1241844#file-route-php
   *
   * @param  string $resourceUri A Request URI
   * @return bool
   */
  public function matches($resourceUri, $verb = null)
  {
    if(is_null($verb)){
      $verb = 'GET';
    }
    
    $exists = false;
    foreach($this->perVerb as $key => $value){
      if($key == $verb && $this->checkMatches($value, $resourceUri)){
        $exists = true;
        continue;

      }
    }
    return $exists;
  }

  private function checkMatches($pattern, $resourceUri)
  {
    //Convert URL params into regex patterns, construct a regex for this route, init params
    $patternAsRegex = preg_replace_callback(
        '#:([\w]+)\+?#',
        array($this, 'matchesCallback'),
        str_replace(')', ')?', (string)$pattern)
    );
    if (substr($this->pattern, -1) === '/') {
        $patternAsRegex .= '?';
    }

    $regex = '#^' . $patternAsRegex . '$#';

    if ($this->caseSensitive === false) {
        $regex .= 'i';
    }

    //Cache URL params' names and values if this route matches the current HTTP request
    if (!preg_match($regex, $resourceUri, $paramValues)) {
        return false;
    }
    foreach ($this->paramNames as $name) {
        if (isset($paramValues[$name])) {
            if (isset($this->paramNamesPath[$name])) {
                $this->params[$name] = explode('/', urldecode($paramValues[$name]));
            } else {
                $this->params[$name] = urldecode($paramValues[$name]);
            }

        }
    }

    return true;
  }


  public function setCallable($callable)
  {
    $matches = array();
    if (is_string($callable) && preg_match('!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!', $callable, $matches)) {
        $class = $matches[1];
        $method = $matches[2];
        $callable = function() use ($class, $method) {
            static $obj = null;
            if ($obj === null) {
                $obj = new $class;
            }
            return call_user_func_array(array($obj, $method), func_get_args());
        };
    }

    if (!is_callable($callable)) {
        throw new \InvalidArgumentException('Route callable must be callable');
    }

    $this->callable = $callable;
  }
}
