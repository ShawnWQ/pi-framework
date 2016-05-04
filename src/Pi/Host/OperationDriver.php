<?hh

namespace Pi\Host;

use Pi\EventManager,
    Pi\Interfaces\ICacheProvider,
    Pi\Interfaces\DtoMappingMetadataInterface,
    Pi\Interfaces\DtoMetadataInterface,
    Pi\Common\ClassUtils,
    Pi\Common\Mapping\Driver\AbstractMappingDriver,
    Pi\Common\Mapping\ClassMetadata,
    Pi\Common\Mapping\ClassFieldMapping,
    Pi\Host\Mapping\OperationMappingType,
    Pi\Host\Operation;

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
		parent::__construct($paths, $em, $cache);
	}
	
	public static function create($paths = array(), EventManager $em, ICacheProvider $cache)
	{
		return new self($paths, $em, $cache);
	}

	public function loadMetadataForClass(string $className, DtoMetadataInterface $entity)
    {
        if(!$entity instanceof ServiceMeta) {
            throw new \InvalidArgumentException("The implementation of DtoMetadataInterface must be Operation");
        }
        $reflClass = $entity->getReflectionClass();
        $parent =  $reflClass->getParentClass();
        $methods = $this->getClassMethods($entity);
        $this->loadMetadataForService($entity, $reflClass);
    }

    public function loadMetadataForOperation(string $operationName, DtoMetadataInterface $operation)
    {
        if(!$operation instanceof Operation) {
            throw new \InvalidArgumentException("The implementation of DtoMetadataInterface must be Operation");
        }
        $reflClass = $operation->getReflectionClass();
        $parent =  $reflClass->getParentClass();
        $this->mapBaseMappings($operation, $reflClass);
        $methods = $this->getClassMethods($operation);
        $this->mapBaseEntityAttributes($operation, $reflClass);
    }

    protected function mapBaseMappings(DtoMetadataInterface $entity, ?\ReflectionClass $reflClass = null) : void
    {
        if($reflClass == null) {
          $reflClass = $entity->getReflectionClass();  
        }
        
        $parent =  $reflClass->getParentClass();
        $methods = $this->getClassMethods($entity);

        foreach ($methods as $method) {
            /* Filter for the declaring class only. Callbacks from parent
            * classes will already be registered.
            */
            if ($method->getDeclaringClass()->name !== $reflClass->name) {
             //continue;
            }
            if(count($method->getAttributes()) === 0) {
                continue;
            }

            // if(não existe attributo para haver lifecycles, continue)
            $mapping = new ClassFieldMapping();

            $methodName = ClassUtils::getMethodName($method->getName());

            $mapping->setFieldName($methodName);
            $isMapping = true;

            $params = $method->getParameters();

            if(!is_array($params) || count($params) == 0 || is_null($params[0]->getClass())
                    || count($method->getAttributes()) === 0) {
                continue;
            }
            // if not a action service, return
            $requestType = $params[0]->getClass()->getName();
            $mapping->opts->add(Pair{'requestType', $requestType});
            
            if($isMapping) {
                die(print_r($mapping));
                $entity->mapField($mapping);
            }
        }
    }

    protected function loadMetadataForService(DtoMetadataInterface $entity, ?\ReflectionClass $reflClass = null) : void
    {
        if($reflClass == null) {
          $reflClass = $entity->getReflectionClass();  
        }
        
        $parent =  $reflClass->getParentClass();
        $methods = $this->getClassMethods($entity);
        $className = $reflClass->name;
        $this->mapOperationMap($entity, $reflClass, $methods, $className);
    }

    protected function mapOperationMap(DtoMetadataInterface $entity, \ReflectionClass $reflClass, $methods, string $className)
    {

        foreach ($methods as $method) {
            $name = $method->name;
            $params = $method->getParameters();

            if(!is_array($params) || count($params) == 0 || is_null($params[0]->getClass())
                || count($method->getAttributes()) === 0) {
                continue;
            }
            // if not a action service, return
            $requestType = $params[0]->getClass()->getName();
            
            /* Filter for the declaring class only. Callbacks from parent
             * classes will already be registered.
             */
            if ($method->getDeclaringClass()->name !== $reflClass->name) {
                //continue;
            }

            // if(não existe attributo para haver lifecycles, continue)
            $mapping = array();
            $methodName = ClassUtils::getMethodName($method->getName());
            $mapping['name'] = $requestType;
            $mapping['method'] = $methodName ?: 'get';
            $isMapping = false;
            $verbs = null;
            $restPath = null;

            foreach($method->getAttributes() as $attrKey => $attrValue) {

                switch($attrKey){

                    case 'Route':
                        $mapping['route'] = $restPath = $attrValue[0];
                        $isMapping = true;
                    break;
                    
                    case 'Method':
                        $mapping['verbs'] = $verbs = $attrValue !== null 
                            && in_array(strtolower($attrValue[0]), array('get', 'post', 'put', 'delete'))
                            ? array(strtoupper($attrValue[0]))
                            : array('GET');
                        $isMapping = true;
                    break;

                    case 'Subscriber':
                        $mapping['Subscriber'] = true;
                        $isMapping = true;
                    break;

                    default:
                    break;
                }
            }
            
            if($isMapping) {
                $entity->mapFieldArray($mapping);
                HostProvider::instance()->routes->add($restPath, $className, $requestType, $verbs ?: array('GET'));
            }
        }
    }
}