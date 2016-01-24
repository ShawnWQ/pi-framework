<?hh

namespace Pi\Host;

use Pi\Common\Mapping\Driver\AbstractMappingDriver;
use Pi\Host\Mapping\ServiceMappingType;
use Pi\Host\Operation;

/**
 * Operation Driver
 *
 * The default implementation to read metadata for operations from attributes
 * If the code is consuming the driver, then the operation wasn't cached yet
 */
class ServiceAttributeDriver extends AbstractMappingDriver {

  protected static $authBasic = 'basic';
	public static function create($paths = array())
	{
		return new self($paths);
	}

	public function loadMetadataForClass(string $className, Operation $entity)
	{
		$reflClass = $entity->getReflectionClass();

    // Lifecycle events through mehtods
  	foreach ($reflClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {

  		/* Filter for the declaring class only. Callbacks from parent
	     * classes will already be registered.
	     */
	    if ($method->getDeclaringClass()->name !== $reflClass->name) {
	    	continue;
	    }

  		foreach($method->getAttributes() as $key => $value) {
  			$mapping = array();
  			$methodName = ClassUtils::getMethodName($method->getName());
  			$mapping['fieldName'] = $methodName;
  			$mapping['name'] = $methodName; //$method->getName()

  			switch($key) {
  				case ServiceMappingType::Auth:
  				$mapping['auth'] = self::$authBasic;
  				break;
  			}

  			$entity->mapField($mapping);
  		}
  	}
	}
}
