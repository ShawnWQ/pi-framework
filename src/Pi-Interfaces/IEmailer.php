<?hh

namespace Pi\Interfaces;

use Pi\ServiceModel\Email;


interface IEmailer {

  public function email(Email $request);
}
