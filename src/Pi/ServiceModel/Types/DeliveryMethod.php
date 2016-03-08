<?hh

namespace Pi\ServiceModel\Types;

/**
 * A delivery method is a standardized procedure for transferring the product or service to the destination of fulfillment chosen by the customer. Delivery methods are characterized by the means of transportation used, and by the organization or group that is the contracting party for the sending organization or person. 
 */
enum DeliveryMethod : int {

	DeliveryModeDirectDownload = 1;
	DeliveryModeFreight = 2;
	DeliveryModeMail = 3;
	DeliveryModeOwnFleet  = 4;
	DeliveryModePickUp = 5;
	DHL = 6;
	FederalExpress = 7;
	UPS = 8;
	
}