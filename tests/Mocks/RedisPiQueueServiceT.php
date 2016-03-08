<?hh

namespace Mocks;

use Pi\Service;

class RedisPiQueueServiceT extends Service {
	
	public function default(RedisPiQueueServiceTRequest $request)
	{
		$response = new RedisPiQueueServiceTResponse();
		return $response;
	}
}