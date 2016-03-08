<?hh
use Pi\AmqbRabbit\AmqbRabbitFactory;
use Pi\AmqbRabbit\AmqbRabbitQueueClientFactory;
use Pi\AmqbRabbit\AmqbRabbitQueueClient;

class AmqbRabbitQueueClientFactoryTest extends \PHPUnit_Framework_TestCase {

	protected AmqbRabbitFactory $factory;

	public function setUp()
	{
		$this->factory = new AmqbRabbitFactory();
	}

	public function testCanCreateQueueClient()
	{
		$clientFactory = new AmqbRabbitQueueClientFactory($this->factory);
		$client = $clientFactory->createMessageQueueClient();
		$this->assertTrue($client instanceof AmqbRabbitQueueClient);
	}
	
}