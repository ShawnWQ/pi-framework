<?hh

namespace Pi\ServiceModel\Types;

enum ItemAvailability : int {
	Discontinued = 1;
	InStock = 2;
	InStoreOnly = 3;
	LimitedAvailability = 4;
	OnlineOnly = 5;
	OutOfStock = 6;
	PreOrder = 7;
	SoldOut = 8;
}
