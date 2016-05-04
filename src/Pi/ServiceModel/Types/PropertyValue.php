<?hh

namespace Pi\ServiceModel\Types;

/**
 * A property-value pair, e.g. representing a feature of a product or place.
 * Use the 'name' property for the name of the property. If there is an additional human-readable version of the value, put that into the 'description' property.
 * @example 
 * <div itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
 */
class PropertyValue extends Intangible {

	protected \MongoId $id;

	/**
	 * @example Approx. Weight
	 * <span itemprop="name">Approx. Weight</span>
	 */
	protected string $name;

	/**
	 * @example 
	 * <span itemprop="value">450</span>
	 */
	protected $value;

	protected ?int $maxValue;

	protected ?int $minValue;

	protected ?string $unitCode;

	/**
	 * @example <span itemprop="unitText">gram</span>
	 */
	protected ?string $unitText;

	protected string $description;

	protected ?string $image;

	protected ?string $alternateName;
}