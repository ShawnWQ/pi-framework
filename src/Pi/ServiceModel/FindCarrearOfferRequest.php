<?hh

namespace Pi\ServiceModel;

class FindCarrearOfferRequest extends RequestQueryAbstract {
	
	protected $carrers;

	public function getCarrers()
	{
		return $this->carrers;
	}

	public function setCarrers($data)
	{
		$this->carrers = $data;
	}
}