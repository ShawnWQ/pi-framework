<?hh

namespace SpotEvents\ServiceModel;

class FindPaymentRequest {
	
	protected $authorId;

	<<ObjectId>>
	public function getAuthorId()
	{
		return $this->authorId;
	}

	public function setAuthorId(\MongoId $id)
	{
		$this->authorId = $id;
	}
}