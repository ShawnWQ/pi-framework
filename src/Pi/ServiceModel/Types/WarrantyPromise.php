<?hh

namespace Pi\ServiceModel\Types;

/**
 * A structured value representing the duration and scope of services that will be provided to a customer free of charge in case of a defect or malfunction of a product.
 */
class WarrantyPromise extends Thing {

	protected $durationOfWarranty;

	protected WarrantyScope $warrantyScope;

}