<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class FindPaymentResponse extends Response {
	
	protected $payments;

	public function getPayments()
	{
		return $this->payments;
	}

	public function setPayments($values)
	{
		$this->payments = $values;
	}
}