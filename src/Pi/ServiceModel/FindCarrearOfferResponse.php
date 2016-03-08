<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class FindCarrearOfferResponse extends Response {
	
	protected $carrers = array();

	public function getCarrers()
	{
		return $this->carrers;
	}

	public function setCarrers($data)
	{
		$this->carrers = $data;
	}
}