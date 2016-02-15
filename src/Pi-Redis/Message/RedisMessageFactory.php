<?hh

namespace Pi\Redis\Message;


use Pi\Interfaces\IMessageFactory;




class RedisMessageFactory implements IMessageFactory {

	protected $messageService;

	protected $clientFactory;

	public function __construct()
	{
		$this->messageService = new RedisMessageService($this);
		$this->clientFactory = new RedisMessageQueueClientFactory($this);
	}
	
	public function createMessageProducer()
	{
		return new RedisMessageProducer($this->messageService)
	}

	public function createMessageQueueClient()
	{
		return $this->clientFactory->createMessageQueueClient();
	}
}