<?hh
namespace Pi\Host;
use Pi\Host\ServiceExecuteFn;
use Pi\Interfaces\IServiceExecutor;
use Pi\Interfaces\IRequest;

class ServiceExecutor
	implements IServiceExecutor {
	

	public static function createExecutionFn($requestType, $serviceType, $method, $handler) : ServiceExecuteFn
	{
		// service params
		// request dto params
		// requ
		$req = Set{};

		$fn = new ServiceExecuteFn(function(IRequest $request) use($handler, $method){
			$serviceInstance = $handler($request, $request->dto());
			$serviceInstance->setAppHost(\Pi\Host\HostProvider::instance());
			//$r = $serviceInstance->$method($request->dto());
			$r = call_user_func(array($serviceInstance, $method), $request->dto());
			return $r;
		});
		return $fn;
	}
	public static function reset() : void
	{

	}
}
