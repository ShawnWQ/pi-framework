<?hh

namespace Pi\Odm\Mapping;
use Pi\EventManager;
use Pi\Odm\Events;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Odm\Interfaces\IMappingDriver;
use Pi\Common\Mapping\AbstractMetadataFactory;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;
/**
 * The ClassMetadataFactory is used to create ClassMetadata objects that contain all the
 * metadata mapping informations of a class which describes how a class should be mapped
 * to a document database.
 */
class EntityMetaDataFactory extends AbstractMetadataFactory implements IContainable, IEntityMetaDataFactory {

	public function newEntityMetadataInstance(string $documentName)
   	{
   		return new EntityMetaData($documentName);
   	}
}
