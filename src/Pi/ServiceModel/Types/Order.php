<?hh

namespace Pi\ServiceModel\Types;

class Order extends Intangible {

	protected $acceptedOffer;

	protected PostalAddress $billingAddress;

	/**
	 * An entity that arranges for an exchange between a buyer and a seller. In most cases a broker never acquires or releases ownership of a product or service involved in an exchange. If it is not clear whether an entity is a broker, seller, or buyer, the latter two terms 
	 */
	protected $broker;

	/**
	 * A number that confirms the given order or payment has been received.
	 */
	protected $confirmationNumber;

	/**
	 * Party placing the order or paying the invoice.
	 */
	protected $customer;

	/**
	 * Any discount applied (to an Order).
	 */
	protected $discount;

	/**
	 * Code used to redeem a discount.
	 */
	protected string $discountCode;

	/**
	 * The currency (in 3-letter ISO 4217 format) of the discount.
	 */
	protected $discountCurrency;

	/**
	 * Was the offer accepted as a gift for someone other than the buyer.
	 */
	protected bool $isGift;

	protected \DateTime $orderDate;

	protected ParcelDelivery $orderDelivery;

	protected string $orderNumber;

	protected OrderStatus $orderStatus;

	/**
	 * The item ordered.
	 */
	protected $orderedItem;

	protected $partOfInvoice;

	protected \DateTime $paymentDue;

	protected PaymentMethod $paymentMethod;

	/**
	 * An identifier for the method of payment used (e.g. the last 4 digits of the credit card).
	 */
	protected string $paymentId;

	/**
	 * An entity which offers (sells / leases / lends / loans) the services / goods. A seller may also be a provider. Supersedes vendor, merchant.
	 */
	protected $seller;
}	

