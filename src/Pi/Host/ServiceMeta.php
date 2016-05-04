<?hh

namespace Pi\Host;

use Pi\Common\Mapping\AbstractMetadata;




/**
 * Wrapper to store all services metadata
 *
 * Metadata fields:
 * - name: Service Name (not the class name), not unique
 * - className: Service ClassName
 * - route: Base API route for all Operations
 * - auth: authentication filter for all Operations
 *
 */
class ServiceMeta extends AbstractMetadata {

  public function mapFieldArray(array $mapping) : void
  {
    $name = $mapping['name'];
    if($this->reflClass->hasProperty($name)) {
      $reflProp = $this->reflClass->getProperty($name);
      $reflProp->setAccessible(true);
      $this->reflFields[$name] = $reflProp;
    }

    $this->fieldMappings[$name] = $mapping;
  }
}
