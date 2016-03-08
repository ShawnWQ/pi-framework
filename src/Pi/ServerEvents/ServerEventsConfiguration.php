<?hh

namespace Pi\ServerEvents;

class ServerEventsConfiguration {

  protected $streamPath;

  protected $heartbeatPath;

  protected $unRegisterPath;

  protected $subscribersPath;

  protected $limitaoAuthenticatedUsers = false;

  protected $timeout;

  protected $heartbeatInterval;

  protected $notifyChannelOfSubscriptions = true;


  /**
   * Get or set the the entry-point for Server Sent Events
   * @var [type]
   */
  public function streamPath($value = null)
  {
    if($value === null) return $this->streamPath;
    $this->streamPath = $value;
  }

  /**
   * Where to unregister your subscription
   * @param [type] $value [description]
   */
  public function unRegisterPath($value = null)
  {
    if($value === null) return $this->unRegisterPath;
    $this->unRegisterPath = $value;
  }

  /**
   * Where to send heartbeat pulses
   * @param [type] $value [description]
   */
  public function heartbeatPath($value = null)
  {
    if($value === null) return $this->heartbeatPath;
    $this->heartbeatPath = $value;
  }

  /**
   * Where to view public info of channel subscribers
   * @param [type] $value [description]
   */
  public function subscribersPath($value = null)
  {
    if($value === null) return $this->subscribersPath;
    $this->subscribersPath = $value;
  }

  /**
   * Return `401 Unauthorized` to non-authenticated clients
   * @param [type] $value [description]
   */
  public function limitToAuthenticatedUsers($value = null)
  {
    if($value === null) return $this->limitaoAuthenticatedUsers;
    $this->limitaoAuthenticatedUsers = $value;
  }

  /**
   * How long to wait for heartbeat before unsubscribing
   * @param  [type] $value [description]
   * @return [type]        [description]
   */
  public function timeout($value = null)
  {
    if($value === null) return $this->timeout;
    $this->timeout = $value;
  }

  /**
   * Client Interval for sending heartbeat messages
   * @param [type] $value [description]
   */
  public function heartbeatInterval($value = null)
  {
    if($value === null) return $this->heartbeatInterval;
    $this->heartbeatInterval = $value;
  }

  /**
   * Send notifications when subscribers join/leave
   * @param [type] $value [description]
   */
  public function notifyChannelOfSubscriptions($value = null)
  {
    if($value === null) return $this->notifyChannelOfSubscriptions;
    $this->notifyChannelOfSubscriptions = $value;
  }
}
