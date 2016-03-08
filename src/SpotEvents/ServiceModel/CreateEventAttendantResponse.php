<?hh

namespace SpotEvents\ServiceModel;
use SpotEvents\ServiceModel\Types\PaymentDto;
use Pi\Response;

class CreateEventAttendantResponse extends Response {
	
	protected $payment;

	public function setPayment(PaymentDto $dto)
    {
        $this->payment = $dto;
    }

    public function getPayment()
    {
        return $this->payment;
    }
}