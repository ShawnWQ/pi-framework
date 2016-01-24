<?hh

namespace Pi\ServiceModel\Types;

class ContactType extends StructuredValue {

	/**
	 * The location served by this contact point (e.g., a phone number intended for Europeans vs. North Americans or only within the United States).
	 */
	protected $areaServed;

	protected $availableLanguage;

	protected $contactOption;

	/**
	 * A person or organization can have different contact points, for different purposes. 
	 * For example, a sales contact point, a PR contact point and so on. 
	 * This property is used to specify the kind of contact point.
	 */
	protected $contactType;

	protected string $email;

	protected string $faxNumber;

	/**
	 * @var OpeningHoursSpecification[]
	 */
	protected array $hoursAvailable;

	protected string $telephone;

}