<?hh

namespace Pi;
use Pi\Interfaces\IEventManager;
use Pi\Interfaces\IEventArgs;
use Pi\Interfaces\IEventSubscriber;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Common\ClassUtils;

class EventManager
  implements IEventManager, IContainable {

    protected $listeners;

    protected $listenersT;

    public function ioc(IContainer $ioc)
    {

    }

    public function __construct()
    {
      $this->listeners = array();
      $this->listenersT = array();
    }

    public function dispatch($name, ?IEventArgs $args)
    {

      if(isset($this->listeners[$name])) {
        $args = is_null($args) ? EventArgs::getInstance() : $args;

        foreach($this->listeners[$name] as $listener) {
          $listener->$name($args);
        }
      }

      if(isset($this->listenersT[$name])) {
        $args = is_null($args) ? EventArgs::getInstance() : $args;

       foreach($this->listenersT[$name] as $dtoName => $listener) {
          
          $dto = new $dtoName();
          ClassUtils::mapDto($args, $dto);
          $listener($dto);
        }
      }
    }

    public function add($events, $listener)
    {
      $id = spl_object_hash($listener);

      foreach((array)$events as $event) {
        $this->listeners[$event][$id] = $listener;
      }
    }

    public function addTyped(string $event, string $dtoName, $callable)
    {
        $this->listenersT[$event][$dtoName] = $callable;
    }

    public function remove($events, $listener)
    {
      $id = spl_object_hash($listener);

      foreach((array)$events as $event) {
        if(isset($this->listeners[$event][$id])) {
          unset($this->listeners[$event][$id]);
        }
      }
    }

    public function subscribe(EventSubscriber $subscriber)
    {
      $this->add($subscriber->getEventsSubscribed(), $subscriber);
    }

    public function unsubscribe(EventSubscriber $subscriber)
    {
      $this->remove($subscriber->getEventsSubscribed(), $subscriber);
    }

    /**
     * Checks whether and event has any registered listeners.
     * @param string $name event name
     */
    public function has($name)
    {
      return isset($this->listeners[$name]) && $this->listeners[$name];
    }

    public function getListeners($name = null)
    {
      return $name ? $this->listeners[$name] : $this->listeners;
    }

    public function getInvocationList($eventName)
    {
      throw new \Pi\NotImplementedException();
    }
}
