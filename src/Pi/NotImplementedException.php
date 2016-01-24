<?hh

namespace Pi;

/**
 * Not implemented exception
 *
 * An exception to throw not implemented/written tests and code methods
 * I hope any of this exception will ever be caught in diagnosis!
 */
class NotImplementedException extends \Exception {

  public function __construct($message = "This method/test was not implemented yet")
  {
    parent::__construct($message);
  }
}
