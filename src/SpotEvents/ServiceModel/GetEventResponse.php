<?hh

namespace SpotEvents\ServiceModel;	

use Pi\Response;
use SpotEvents\ServiceModel\DTO\EventDto;

class GetEventResponse extends Response {

	public function __construct(protected EventDto $event)
	{

	}

	public function getEvent()
	{
		return $this->event;
	}
}