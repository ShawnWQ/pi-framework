<?hh

namespace Pi\Client;
use Pi\Interfaces\IMessage;

class PiJsonClient
  extends ServiceClientBase {

    protected $ch;
    protected $hostname;

    public function __construct($serviceHostname)
    {
        $this->ch = \curl_init();
        $this->hostname = $serviceHostname;
    }

    public function get($request)
    {
      $json = json_encode($request);
      $uri = '/';
      $requestUri = sprintf('http://%s%s', $this->hostname, $uri);
      
      curl_setopt($this->ch, CURLOPT_URL, $requestUri);
      curl_setopt($this->ch, CURLOPT_HEADER, 1);
      if( ! $result = curl_exec($this->ch)) 
      { 
        trigger_error(curl_error($this->ch)); 
      } 

      return $result;
    }

    public function send(TRequest $request)
    {
      return $this->get($request);
    }
    
   public function publish($type, $messageBody)
    {

    }
      public function publishMessage($type, IMessage $message)
    {

    }

    public function dispose()
    {
      curl_close($this->ch);
    }

  }
