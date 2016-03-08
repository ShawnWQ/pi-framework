<?hh

namespace Pi\Redis\Message;


use Pi\Interfaces\IMessageQueueClientFactory;


class RedisMessageQueueClientFactory implements IMessageQueueClientFactory {

	public function __construct(
		protected RedisMessageFactory $factory
	)
	{
		
	}
	
	public function createMessageQueueClient()
	{
	
	}
}