<?hh

namespace Pi\Interfaces;

interface HydratorFactoryInterface {

	public function hydrate($document, $data);

	public function generateHydratorClass(DtoMetadataInterface $entity, string $hydratorClassName, string $fileName);

	public function getHydratorForClass($document) : HydratorInterface;

	public function getHydrator(string $className) : HydratorInterface;
}