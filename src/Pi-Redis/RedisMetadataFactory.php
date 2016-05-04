<?hh

namespace Pi\Redis;

use Pi\Common\Mapping\AbstractMetadataFactory,
	Pi\Common\Mapping\ClassMetadata;




class RedisMetadataFactory extends AbstractMetadataFactory {
	
	public function newEntityMetadataInstance(string $documentName)
	{
		return new ClassMetadata($documentName);
	}
}