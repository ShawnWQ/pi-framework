<?hh

namespace SpotEvents\ServiceModel;

class GetTicketRequest {

	public function __construct(protected ?\MongoId $id = null)
	{

	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}
}