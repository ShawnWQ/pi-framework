<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:35 AM
 */

namespace SpotEvents\ServiceModel\Types;


enum PaymentStatus : int{

    Created = 1;
    PendingPayment = 2;
    PaymentReceived = 3;
    PaymentExpired = 4;
}