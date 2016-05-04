<?hh

namespace Pi\Realtime\ServicesInterface;

use ElephantIO\Client as Elephant;

class ElephantProvider {

	public function __construct(protected Elephant $client)
	{
		$client->init();
	}

	public function publish(string $channel, $dto)
	{
		$this->client->send(
		    ElephantIOClient::TYPE_EVENT,
		    null,
		    null,
		    json_encode(array('name' => $channel, 'args' => $dto))
		);
	}
}