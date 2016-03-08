<?hh

namespace Pi\ServiceModel\Types;

enum OfferItemCondition : int {
	DamagedCondition = 1;
	NewCondition = 2;
	RefurbishedCondition = 3;
	UsedCondition = 4;
}