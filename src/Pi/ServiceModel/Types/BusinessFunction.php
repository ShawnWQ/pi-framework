<?hh

namespace Pi\ServiceModel\Types;

/**
 * The business function specifies the type of activity or access (i.e., the bundle of rights) offered by the organization or business person through the offer. Typical are sell, rental or lease, maintenance or repair, manufacture / produce, recycle / dispose, engineering / construction, or installation. Proprietary specifications of access rights are also instances of this class. 
 */
enum BusinessFunction : int {
	ConstructionInstallation = 1;
	Dispose = 2;
	LeaseOut = 3;
	Maintain = 4;
	ProvideService = 5;
	Repair = 6;
	Sell = 7;
	Buy = 8;
}