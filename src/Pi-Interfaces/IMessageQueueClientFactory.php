<?hh

namespace Pi\Interfaces;

interface IMessageQueueClientFactory {
	
  public function createMessageQueueClient();
}
