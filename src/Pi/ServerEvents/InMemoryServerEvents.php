<?hh

namespace Pi\ServerEvents;
use Pi\ServerEvents\Interfaces\IServerEvents;
use Pi\ServerEventsInterfaces\IEventSubscription;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class InMemoryServerEvents implements IServerEvents, IContainable {

  protected $onSubscribe;

  protected $onUnsubscribe;

  protected $notifyJoin;

  protected $notifyLeave;

  protected $notifyHeartbeat;

  protected $notifyChannelOfSubscription;


  protected Map<string,IEventSubscription> $subscriptions = Map{};

  protected $channelSubscriptions;

  protected $userIdSubscriptions;

  protected $userNameSubscriptions;

  protected $sessionSubscriptions;

  

  public function __construct()
  {
    $this->reset();
  }

  public function ioc(IContainer $ioc)
  {

  }

  public function serialize(object $message)
  {
    return json_encode($message);
  }

  // External API's
  public function notifyAll(string $selector, object $message) : void
  {
    foreach($this->subscriptions as $id => $sub){
      if($sub === null) continue;
      $sub->publish($selector, $this->serialize($message));
    }
  }

  public function notifyChannel(string $channel, string $selector, object $message) : void
  {
    $this->notify($this->channelSubscriptions, $channel, $channel . "@" . $selector, $message, $channel);
  }

  public function notifySubscription(string $subscriptionId, string $selector, string $message, ?string $channel = null) : void
  {
    $this->notify($this->subscriptions, $subscriptionId, $selector, $message, $channel);
  }

  public function notifyUserId(string $userId, string $selector, object $message, ?string $channel = null) : void{
    $this->notify($this->userIdSubscriptions, $userId, $selector, $message, $channel);
  }

  public function notifyUserName(string $userName, string $selector, object $message, ?string $channel = null) : void{
    $this->notify($this->userNameSubscriptions, $userName, $selector, $message, $channel);
  }

  public function notifySession(string $pipid, string $selector, object $message, ?string $channel = null) : void{
    $this->notify($this->sessionSubscriptions, $pipid, $selector, $message, $channel);
  }

  protected function notify(Map<string,IEventSubscription> $map, string $key,
            string $selector, $message, ?string $channel = null) : void
  {

  }

  public function getSubscription(string $id) : ?IEventSubscription
  {
    if($id === null) return null;
    $subs = array();
    return $this->subscriptions[$id];
  }

  public function getSubscriptionInfo(string $id) : SubscriptionInfo
  {

  }

  public function getSubscriptionInfosByUserId(string $userId) : Vector<SubscriptionInfo>
  {

  }

  // Admin API's
  public function register(IEventSubscription $subscription, ?Map<string,string> $connectArgs = null)
  {

  }

  public function unRegister(string $subscriptionId) : void
  {

  }

  public function getNextSequence(string $sequenceId) : long
  {

  }

  // Client API's
  public function getSubscriptionsDetails(?string $channel = null) : Vector<Map<string,string>>
  {

  }

  public function pulse(string $subscriptionId) : bool
  {

  }

  // Clear all Registrations
  public function reset() : void
  {
    $this->subscriptions = Map{};
    $this->channelSubscriptions = Map {};
    $this->userIdSubscriptions = Map{};
    $this->userNameSubscriptions = Map{};
    $this->sessionSubscriptions = Map{};

  }
  public function start() : void
  {
      header('Content-Type: text/event-stream');
      header('Cache-Control: no-cache');

      @set_time_limit(0);//disable time limit

      //send the proper header
      header('Content-Type: text/event-stream');
      header('Cache-Control: no-cache');

      header('Transfer-encoding: chunked');

      //prevent buffering
      if(function_exists('apache_setenv')){
          @apache_setenv('no-gzip',1);
      }

      @ini_set('zlib.output_compression',0);
      @ini_set('implicit_flush',1);
      while (ob_get_level() != 0) {
          ob_end_flush();
      }
      ob_implicit_flush(1);

      $start = time();//record start time
      echo 'retry: '.(10*1000)."\n";	//set the retry interval for the client

      //keep the script running
      while(true){
          echo ': starting\n\n';
          //if(SSEUtils::time_mod($start,$this->keep_alive_time) == 0){
          //No updates needed, send a comment to keep the connection alive.
          //From https://developer.mozilla.org/en-US/docs/Server-sent_events/Using_server-sent_events
          echo ': '.sha1(mt_rand())."\n\n";
          //}

          //start to check for updates
          foreach($this->_handlers as $event=>$handler){
              if($handler->check()){//check if the data is avaliable
                  $data = $handler->update();//get the data
                  $this->id++;
                  //SSEUtils::sseBlock($this->id,$event,$data);
                  //make sure the data has been sent to the client
                  @ob_flush();
                  @flush();
              }
              else {
                  continue;
              }
          }

          //break if the time excceed the limit
          //if($this->exec_limit != 0 && SSEUtils::time_diff($start) > $this->exec_limit) break;
          //sleep
          usleep($this->sleep_time*1000000);
      }
  }
  public function stop() : void
  {
    $this->reset();
  }
}
