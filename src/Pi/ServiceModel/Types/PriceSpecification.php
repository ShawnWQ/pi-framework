<?hh

namespace Pi\ServiceModel\Types;

class PriceSpecification extends Thing {

	/**
	 * The interval and unit of measurement of ordering quantities for which the offer or price specification is valid. This allows e.g. specifying that a certain freight charge is valid only for a certain quantity.
	 */
	protected $eligibleQuantity;

	/**
	 * The transaction volume, in a monetary unit, for which the offer or price specification is valid, e.g. for indicating a minimal purchasing volume, to express free shipping above a certain order volume, or to limit the acceptance of credit cards to
	 */
	protected PriceSpecification $eligibleTransactionVolume;

	protected $maxPrice;

	protected $minPrice;

	protected $price;

	protected $priceCurrency;

	protected \DateTime $validFrom;

	protected \DateTime $validThrough;

	/**
	 * Specifies whether the applicable value-added tax (VAT) is included in the price specification or not.
	 */
	protected bool $valueAddedTaxIncluded;
}
