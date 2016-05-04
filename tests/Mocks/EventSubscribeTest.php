<?hh

namespace Mocks;
use Pi\EventSubscriber;

class EventSubscribeTest
  extends EventSubscriber {

    public function getEventsSubscribed()
    {
      return array('test');
    }
    protected $t = 'test';
    public function test()
    {
      return $this->t;
    }
}
