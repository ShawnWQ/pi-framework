<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class FindEventResponse extends Response{
	
	protected $events;

	public function setEvents($events)
	{
		$this->events = $events;
	}

	public function getEvents()
	{
		return $this->events;
	}
}