<?hh

namespace Pi\ServerEvents;
use Pi\ServerEvents\Interfaces\IEventSubscription;
use Pi\Interfaces\IResponse;

class EventSubscription extends SubscriptionInfo implements IEventSubscription {

  protected $log;

  protected $unknowChannel = Set{"*"};

  protected IResponse $response;

  protected $onPublish;

  private $msgId;

  protected $lastPulse;

  public function __construct()
  {
    $this->msgId = 11231232123123;

  }

  public function onPublish((function(IResponse,string) : void) $fn) : void
  {
    $this->onPublish = $fn;
  }
  public function invokeOnPublish(IResponse $response, string $frame)
  {
    $a = $this->onPublish;
    $a($response, $frame);
  }

  public function publish(string $selector, ?string $message = null) : void
  {
    try {
        $msg = $message === null? '' : $message;
        $frame = "id: " . $this->msgId . '\n' .
          'data: ' . $selector . ' ' . $msg . '\n\n';

        //response.OutputStream.Write(frame);
            //      response.Flush();

        if($this->onPublish !== null){
          $this->invokeOnPublish($this->response, $frame);
        }
    }
    catch(\Exception $ex) {
      $this->log->fatal($ex);
      $this->unsubscribe();
    }
  }

  public function pulse() : void
  {
    $this->lastPulse = new \DateTime('now');
  }

  public function lastPulseAt($value = null)
  {
    return $this->lastPulse;
  }

  public function onSubscribe($value = null)
  {
    
  }

  public function unsubscribe() : void
  {

  }
  public function dispose() : void
  {

  }
}
