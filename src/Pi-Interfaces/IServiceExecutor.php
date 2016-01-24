<?hh

namespace Pi\Interfaces;
use Pi\Host\ServiceExecuteFn;

interface IServiceExecutor {

	public static function createExecutionFn($requestType, $serviceType, $method, $handler) : ServiceExecuteFn;

	public static function reset() : void;
}
