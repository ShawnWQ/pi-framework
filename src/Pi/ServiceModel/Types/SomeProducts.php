<?hh

namespace Pi\ServiceModel\Types;

/**
 * A placeholder for multiple similar products of the same kind.
 */
class SomeProducts extends Product {
	
	/**
	 * The current approximate inventory level for the item or items.
	 */
	protected $iventoryLevel;
}