<?hh

namespace Pi\Odm\Interfaces;

use Pi\Odm\Mapping\EntityMetaData,
	Pi\Interfaces\DtoMetadataInterface;




interface IMappingDriver {
  
    public function loadMetadataForClass(string $className, DtoMetadataInterface $entity);

  //public static function create($paths = array());
}
