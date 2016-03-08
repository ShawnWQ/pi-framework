<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IEventArgs;
use Pi\Interfaces\IEventSubscriber;
use Pi\EventSubscriber;

interface IEventManager {

  public function dispatch($name, ?IEventArgs $args);
  public function add($events, $listener);
  public function addTyped(string $event, string $dtoName, $callable);
  public function remove($events, $listener);
  public function subscribe(EventSubscriber $subscriber);
  public function unsubscribe(EventSubscriber $subscriber);
  public function has($name);
  public function getListeners($name = null);
}
