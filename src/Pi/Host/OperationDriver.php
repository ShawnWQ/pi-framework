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
        if(!$entity instanceof Operation) {
            throw new \Excepion('OperationDriver only handles Operation as class Metadata object');
        }
        
        $reflClass = $entity->getReflectionClass();
        $parent =  $reflClass->getParentClass();
        $this->mapBaseMappings($entity, $reflClass);
        $methods = $this->getClassMethods($entity);
        $this->mapBaseEntityAttributes($entity, $reflClass);
    }
}
