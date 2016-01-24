<?hh

namespace Pi\ServiceModel\Types;

class PostalAddress extends ContactPoint {

	/**
	 * The country. For example, USA. 
	 * You can also provide the two-letter ISO 3166-1 alpha-2 country code.
	 */
	protected ISO3166_1 $addressCountry;

	/**
	 * The locality. For example, Mountain View.
	 */
	protected string $addressLocality;

	/**
	 * The region. For example, CA.
	 */
	protected string $addressRegion;

	/**
	 * The post office box number for PO box addresses.
	 */
	protected string $postOfficeBoxNumber;

	/**
	 * The postal code. For example, 94043.
	 */
	protected string $postalCode;

	/**
	 * The street address. For example, 1600 Amphitheatre Pkwy.
	 */
	protected string $streetAddress;
}