<?hh
use Mocks\VerseCreateRequest;
use Pi\Message\MessageFactory;
use Pi\Message\LocalMessageProducer;
use Pi\Message\LocalMessageQueueClientFactory;
use Pi\Message\LocalMessageFactory;
class MessageFactoryTest
  extends \PHPUnit_Framework_TestCase {

  public function testCanCreateMessageFromVerseCreateRequest()
  {
      $body = new VerseCreateRequest();
      $message = MessageFactory::create($body);
      $this->assertNotNull($body);

  }
}
