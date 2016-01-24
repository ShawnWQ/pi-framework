<?hh

namespace Pi\ServiceModel\Types;

/**
 * When a single product is associated with multiple offers (for example, the same pair of shoes is offered by different merchants), then AggregateOffer can be used.
 */
class AggregateOffer extends Offer {

	protected $highPrice;

	protected $lowPrice;

	/**
	 * The number of offers for the product.
	 */
	protected int $offerCount;

	protected $offers;
}