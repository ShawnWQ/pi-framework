<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostCarrearOfferResponse extends Response {

	protected JobCarrerDto $carrer;

	public function getCarrer()
	{
		return $this->carrer;
	}

	public function setCarrer(JobCarrerDto $dto)
	{
		$this->carrer = $dto;
	}
}