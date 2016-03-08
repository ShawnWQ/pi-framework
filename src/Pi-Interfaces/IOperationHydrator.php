<?hh 

namespace Pi\Interfaces;

/**
 * Hydration is the act of populating an object from a set of data.
 */
interface IOperationHydrator {

	public function extract($object);

	public function hydrate(array $data, $object);
}