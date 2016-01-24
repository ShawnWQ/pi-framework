<?hh

namespace Pi\Host;

use Pi\EventManager;
use Pi\Interfaces\ICacheProvider;
use Pi\Common\ClassUtils;
use Pi\Common\Mapping\Driver\AbstractMappingDriver;
use Pi\Host\Mapping\OperationMappingType;
use Pi\Host\Operation;

/**
 * Operation Driver
 *
 * The default implementation to read metadata for operations from attributes
 * If the code is consuming the driver, then the operation wasn't cached yet
 */
class OperationDriver extends AbstractMappingDriver {

	public function __construct(
		array $paths = array(),
		protected EventManager $em,
		protected ICacheProvider $cache)
	{
		parent::__construct($paths);
	}
	
	public static function create($paths = array(), EventManager $em, ICacheProvider $cache)
	{
		return new self($paths, $em, $cache);
	}

	public function loadMetadataForClass(string $className, Operation $entity)
	{
		
		$reflClass = $entity->getReflectionClass();
		if($reflClass->getAttribute('MultiTenant') !== null) {
      		$entity->setMultiTenant(true);

			if($entity->getReflectionClass()->hasProperty('appId')) {
				$appIdProp = $entity->getReflectionClass()->getProperty('appId');
				$entity->setMultiTenantField('appId');
			}
    	}

    	// Lifecycle events through mehtods
    	foreach ($reflClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {

    		/* Filter for the declaring class only. Callbacks from parent
		     * classes will already be registered.
		     */
		    if ($method->getDeclaringClass()->name !== $reflClass->name) {
		    	continue;
		    }

    		foreach($method->getAttributes() as $key => $value) {

    			$methodName = ClassUtils::getMethodName($method->getName());
                if($methodName === 'appId') {
                    continue;
                }
    			$mapping = array(
    				'isEmbedMany' => false,
    				'isEmbedOne' => false,
    				'name' => $methodName,
    				'fieldName' => $methodName);
    			
    			switch($key) {
    				case OperationMappingType::Field:
    					$mapping['type'] = OperationMappingType::Field;
    				break;
					case OperationMappingType::Int:
    					$mapping['type'] = OperationMappingType::Int;
    				break;
    				case OperationMappingType::DateTime:
    					$mapping['type'] = OperationMappingType::DateTime;
    				break;
    				case OperationMappingType::Id:
    					$mapping['type'] = OperationMappingType::Id;
    					$mapping['id'] = $methodName;
    				break;

    				default:
    					$mapping['type'] = OperationMappingType::String;
    			}

    			$entity->mapField($mapping);
    		}
    	}
	}
}
