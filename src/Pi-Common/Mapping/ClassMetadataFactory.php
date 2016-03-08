<?hh

namespace Pi\Common\Mapping;




class ClassMetadataFactory extends AbstractMetadataFactory {
	
	public function newEntityMetadataInstance(string $documentName)
	{
		return new ClassMetadata($documentName);
	}
}