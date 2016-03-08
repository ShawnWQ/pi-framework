<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetCarrearOfferResponse extends Response {
	
	protected JobCarrerDto $carrer;

	public function getCarrer()
	{
		return $this->carrer;
	}

	public function setCarrer($dto)
	{
		$this->carrer = $dto;
	}
}