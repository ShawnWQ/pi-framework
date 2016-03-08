<?hh

namespace Pi\Redis\Message;


use Pi\Interfaces\IMessageHandler;




class RedisMessageHandler implements IMessageHandler {
	
	public function getMessageType()
	{

	}

	public function getMqClient()
	{

	}

	public function process(IMessageQueueClient $client) : void
	{

	}

	public function processQueue(IMessageQueueClient $client, string $queueName) : void
	{

	}

	public function processMessage(IMessageQueueClient $client, $response)
	{

	}
}