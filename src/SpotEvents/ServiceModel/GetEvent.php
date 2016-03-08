<?hh

namespace SpotEvents\ServiceModel;

class GetEvent {

	public function __construct(protected ?\MongoId $eventId = null)
	{

	}

	public function setEventId(\MongoId $id)
	{
		$this->eventId = $id;
	}

	<<ObjectId>>
	public function getEventId()
	{
		return $this->eventId;
	}
}