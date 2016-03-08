<?hh
use Pi\Message\AmqbRabbitMessageFactory;

class AmqbRabbitMessageFactoryTest
  extends \PHPUnit_Framework_TestCase {


    public function testCanCreateMessageProducer()
    {
      $factory = new AmqbRabbitMessageFactory();
      $producer = $factory->createMessageProducer();
      $this->assertInstanceOf('\Pi\Interfaces\IMessageProducer', $producer);
    }
}
