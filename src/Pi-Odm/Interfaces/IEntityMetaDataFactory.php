<?hh

namespace Pi\Odm\Interfaces;

interface IEntityMetaDataFactory {
	
	 public function getMetadataFor(string $className);

	 public function setMetadataFor($className, $class);
}