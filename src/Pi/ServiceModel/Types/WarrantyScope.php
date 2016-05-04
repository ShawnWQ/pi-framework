<?hh

namespace Pi\ServiceModel\Types;

enum WarrantyScope : int {

	LaborBringIn = 1;
	PartsAndLaborBringIn = 2;
	PartsAndLaborPickUp = 3;
}