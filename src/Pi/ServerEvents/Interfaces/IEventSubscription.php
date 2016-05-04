<?hh

namespace Pi\ServerEvents\Interfaces;
use Pi\Interfaces\IDisposable;
use Pi\Interfaces\IResponse;

//Implement IMeta for get meta?
interface IEventSubscription extends IDisposable {
  public function createdAt($value = null);

  public function lastPulseAt($value = null);

  public function channels($value = null);

  public function userId($value = null);

  public function userName($value = null);

  public function displayName($value = null);

  public function sessionId($value = null);

  public function subscriptionId($value = null);

  public function isAuthenticated($value = null);

  public function onSubscribe($value = null);

  public function onPublish((function(IResponse,string) : void) $fn);

  public function unsubscribe() : void;

  public function publish(string $selector, ?string $message = null) : void;

  public function pulse() : void;
}
