<?hh

namespace Pi\ServiceModel\Types;

class Invoice extends Intangible {

	/**
	 * The identifier for the account the payment will be applied to.
	 */
	protected \MongoId $accountId;

	/**
	 * The time interval used to compute the invoice.
	 */
	protected $billingPeriod;

	protected $broker;

	protected $category;

	protected string $confirmationNumber;

	/**
	 * Party placing the order or paying the invoice.
	 */
	protected $customer;

	protected PriceSpecification $minimumPaymentDue;

	protected \Datetime $paymentDue;

	protected PaymentMethod $paymentMethod;

	/**
	 * An identifier for the method of payment used (e.g. the last 4 digits of the credit card).
	 */
	protected $paymentIdMethod;

	protected $paymentStatus;

	/**
	 * The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller. Supersedes carrier.
	 */
	protected $provider;

	/**
	 * The Order(s) related to this Invoice. One or more Orders may be combined into a single Invoice.
	 */
	protected array $referenceOrders;

	/**
	 * The date the invoice is scheduled to be paid.
	 */
	protected \DateTime $schedulePaymentDue;

	/** 
	 * The total amount due
	 */
	protected PriceSpecification $totalPaymentDue;

}