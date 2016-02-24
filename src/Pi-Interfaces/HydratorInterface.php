<?hh 

namespace Pi\Interfaces;


/**
 * Hydration is the act of populating an object from a set of data.
 */
interface HydratorInterface {

	public function extract($object);

	public function hydrate(array $data, $object);
}