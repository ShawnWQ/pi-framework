<?hh

namespace Pi\ServiceModel\Types;

enum OrderStatus : int {
	OrderCancelled = 1;
	OrderDelivered = 2;
	OrderInTransit = 3;
	OrderPaymentDue = 4;
	OrderPickupAvailable = 5;
	OrderProblem = 6;
	OrderProcessing = 7;
	OrderReturned = 8;
}