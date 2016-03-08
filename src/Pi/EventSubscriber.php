<?hh

namespace Pi;
use Pi\Interfaces\IEventSubscriber;

abstract class EventSubscriber
  implements IEventSubscriber {

  public abstract function getEventsSubscribed();
}
