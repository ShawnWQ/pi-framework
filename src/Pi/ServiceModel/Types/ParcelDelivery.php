<?hh

namespace Pi\ServiceModel\Types;

class ParcelDelivery extends Intangible {

	protected string $deliveryAddress;

	protected $deliveryStatus;

	protected \DateTime $expectedArrivalFrom;

	protected \DateTime $expectedArrivalUntil;

	protected DeliveryMethod $hasDeliveryMethod;

	protected $itemShipped;

	protected PostalAddress $originAddress;

	protected $partOfOrder;

	/**
	 * The service provider, service operator, or service performer; the goods producer. Another party (a seller) may offer those services or goods on behalf of the provider. A provider may also serve as the seller. Supersedes carrier.
	 */

	protected $provider;

	/**
	 * Shipper tracking number.
	 */
	protected string $trackingNumber;

	/**
	 * Tracking url for the parcel delivery.
	 */
	protected string $trackingUrl;
}