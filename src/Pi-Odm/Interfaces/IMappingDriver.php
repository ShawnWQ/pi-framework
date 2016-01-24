<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\Mapping\EntityMetaData;

interface IMappingDriver {
  public function loadMetadataForClass(string $className, EntityMetaData $entity);

  public static function create($paths = array());
}
