<?hh

namespace Pi\ServiceModel\Types;

enum PaymentMethod : int {

	ByBankTransferInAdvance = 1;
	ByInvoice = 2;
	Cash = 3;
	CheckInAdvance = 4;
	COD = 5;
	DirectDebit = 6;
	GoogleCheckout = 7
	PayPal = 8;
	PaySwarm = 9;
}