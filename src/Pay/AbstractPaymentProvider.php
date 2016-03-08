<?hh

namespace Pay;

use Pi\Interfaces\IService,
	Pay\Interfaces\PaymentChargeInterface,
	Pay\ServiceModel\ChargeResult,
	Pay\ServiceModel\PaymentCharge;

class PaymentDoc {

	public function __construct(
		protected string $provider
	)
	{

	}
}

interface PaymentChargeInterface {

}

/**
 * Payment Session contain providers information
 */
interface PaymentSessionInterface {


}

interface PaymentRepositoryInterface {

}

class PaymentAuthorizer extends AbstractPaymentProvider {

	public function __construct(
		protected AbstractPaymentProvider $paymentProvider
	)
	{

	}

	public function getPayment()
	{

	}

	public function getPaymentApproval()
	{

	}

	public function updatePayment()
	{

	}

	public function createPayment()
	{

	}

	public function executePayment()
	{

	}

	public function getPaymentSale()
	{

	}

	public function executePaymentSaleRefund()
	{

	}

	public function getPaymentOrder()
	{

	}

	public function executePaymentOrderRefund()
	{

	}

}


abstract class AbstractPaymentProvider {

	protected PaymentAuthorizer $paymentUtils;

	public function __construct(
		protected AppSettingsInterface $appSettings,
		protected AuthProvider $paymentAuthProvider
	)
	{
		$this->paymentUtils = new PaymentAuthorizer($this);
	}

	public function init(IService $paymentService, PaymentSessionInterface $session, PaymentChargeInterface $charge)
	
	public abstract function charge(IService $paymentService, PaymentChargeInterface $charge) : ChargeResult;

	public function onCharge(IService $paymentService, PaymentChargeInterface $charge)
	{
		$chargeRepo = $paymentService->tryResolve('Pay\ServiceInterface\Data\PaymentRepository');
	}


	public function paymentEvents()
	{
		return HostProvider::tryResolve('PaymentEvensInterface') ?: new PaymentEvents();
	}

	public function savePayment(IService $paymentService, PaymentSessionInterface $session, PaymentRepositoryInterface $paymentRepo, ?PaymentChargeInterface $payment = null)
	{

	}

	//public function onSavePayment

	public function loadPaymentInfo(PaymentChargeInterface $charge, Map<string,string> $data)
	{

	}
}