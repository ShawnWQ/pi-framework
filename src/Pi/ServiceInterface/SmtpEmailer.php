<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\IEmailer;
use Pi\ServiceModel\Email;



class SmtpEmailer implements IEmailer {

  public function email(Email $request)
  {
    // create php mail object
    // send smtp mail
    // save in database
  }
}
