<?hh
use Pi\AmqbRabbit\AmqbRabbitFactory;
use Pi\AmqbRabbit\AmqbRabbitQueueClientFactory;
use Pi\AmqbRabbit\AmqbRabbitQueueClient;

class AmqbRabbitQueueClientTest extends \PHPUnit_Framework_TestCase {

	protected AmqbRabbitFactory $clientFactory;
	
	public function setUp()
	{
		$factory = new AmqbRabbitFactory();
		$this->clientFactory = new AmqbRabbitQueueClientFactory($factory);
	}

	public function testCanSubscribe()
	{

	}
}