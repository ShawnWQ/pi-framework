<?hh

namespace Pi\Host;
use Pi\PiFileMapper;
use Pi\Host\ServiceMeta;
use Pi\Interfaces\IService;

class ServiceScanner<TService> {

	protected $appHost;

	public function __construct(protected PiFileMapper $fileMapper)
	{

	}

	public function getMeta() : Map
	{
		$map = $this->fileMapper->getMap();
		$set = Map{};
		foreach($map->getIterator() as $serviceType => $value) {
			$interfaces = class_implements($serviceType);
			if(!in_array('Pi\Interfaces\IService', $interfaces)){
				continue;
			}
			$meta = new ServiceMeta($serviceType);
			$rc = new \ReflectionClass($serviceType);
			$methods = $rc->getMethods();
			foreach($methods as $method){
				$attrs = $method->getAttributes();
        $name = $method->name;
        $params = $rc->getMethod($name)->getParameters();
        if(!is_array($params) || count($params) == 0 || is_null($params[0]->getClass()))
          continue;
        // if not a action service, return

        $requestType = $params[0]->getClass()->getName();
				$meta->add($requestType, $method->name, $attrs);
			}
			$set[$serviceType] = $meta;
		}
		return $set;
	}
}
/*
$parse = function($serviceType) {


			$meta = new \Pi\Host\ServiceMeta($serviceType);
		    $rc = new \ReflectionClass($serviceType);

		    $methods = $rc->getMethods();

		    foreach($methods as $method)
		    {
		      $name = $method->name;
		       $attrs = $rc->getMethod($name)->getAttributes();

		       if(array_key_exists('ServiceRequest', $attrs)) {
		         $requestType = $rc->getMethod($name)->getAttribute('ServiceRequest');
		         if(!is_null($requestType)) {
		        //   $this->services[$requestType[0]] = $serviceType;
		           $this->appHost->log()->debug(
		            sprintf('Registering request type %s for service type %s', $requestType[0], $serviceType)
		          );
		         }

		         if(array_key_exists('RestPath', $attrs)){
		           $restPath = $rc->getMethod($name)->getAttribute('RestPath');
		           if(!empty($restPath)) {
		             $this->appHost->routes()->add($requestType, $restPath);
		             $this->appHost->debug(
		              sprintf('Registering the rest path: %s for request type %s', $restPath, $requestType)
		            );
		           }
		         }
		       }
		    }
		}
	}
 */
