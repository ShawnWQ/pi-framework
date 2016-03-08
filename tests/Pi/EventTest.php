<?hh
use Pi\EventManager;
use Mocks\EventSubscribeTest;
use Mocks\EventTestArgs;

class EventManagerTest
  extends \PHPUnit_Framework_TestCase  {

  protected $manager;

  public function setUp()
  {
    $this->manager = new EventManager();
  }

  public function testCanSubscribeAndGetCalled()
  {
    $subscriber = new EventSubscribeTest();
    $args = new EventTestArgs();
    $this->assertFalse($this->manager->has('test'));
    $this->manager->subscribe($subscriber);
    $this->manager->dispatch('test', $args);
    $this->assertTrue($this->manager->has('test'));


  }
}
