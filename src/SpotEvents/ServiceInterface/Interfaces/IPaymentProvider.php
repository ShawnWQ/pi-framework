<?hh

namespace SpotEvents\ServiceInterface\Interfaces;

interface IPaymentProvider {
	
	public function validate($dto);

	public function executePayment();
}