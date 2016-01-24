<?hh

namespace Pi\ServiceModel\Types;

/**
 * A business entity type is a conceptual entity representing the legal form, the size, the main line of business, the position in the value chain, or any combination thereof, of an organization or business person. 
 */
enum BusinessEntityType : int {
	Business = 1;
	Enduser = 2;
	PublicInstitution = 3;
	Reseller = 4;
}