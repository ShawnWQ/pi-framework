<?hh

namespace Pi\Interfaces;
use Pi\Interfaces\IContainer;

/**
 * Interface to mark the class as managed by IOC container
 */
interface IContainable {

  //public static function iocType() : string;

  public function ioc(IContainer $container);
}
