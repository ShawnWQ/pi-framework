<?hh
use Pi\Message\InMemoryFactory;
use Pi\Message\InMemoryProducer;
use Pi\Message\InMemoryQueueClientFactory;
use Pi\Message\InMemoryQueueClient;
use Pi\Message\InMemoryContext;
use Pi\Message\InMemoryService;
use Mocks\MockEnvironment;
use Mocks\VerseGet;

class Test {

}
class InMemoryTest
  extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
      MockEnvironment::mock();
    }
    public function testAll()
    {
      $host = new \Mocks\BibleHost(new \Pi\HostConfig());
      $host->init();
      $request = new VerseGet();
      $service = $host->tryResolve('IMessageService');

      $service->setAppHost($host);
      // The InMemoryService requrires Requests to be registered before starting
      $service->registerHandler($request);
      $service->start();


      $response = $service->publish($request);

      $this->assertNotNull($response);
      /**
       *   $called = false;
         $service->subscribe(get_class($request), function($r) use(&$called){
           // create message response with $r as body
           $called = true;
         });
       */
    }
}
