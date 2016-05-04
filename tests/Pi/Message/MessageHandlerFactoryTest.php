<?hh

use Pi\Message\MessageHandler;
use Pi\Message\MessageHandlerFactory;
use Pi\Message\InMemoryFactory;
use Pi\Message\InMemoryService;
use Mocks\VerseCreateRequest;

class MessageHandlerFactoryTest
  extends \PHPUnit_Framework_TestCase {

    public function testCanCreateMessageHandler()
    {
      $factory = new InMemoryFactory();
      $service = new InMemoryService($factory);
      $request = new VerseCreateRequest();
      $factory = new MessageHandlerFactory($service, $request, null);

      $handler = $factory->createMessageHandler();
      $this->assertTrue($handler->messageType() === get_class($request));
    }
}
