<?hh 

namespace Pi\Odm\Interfaces;
use Pi\Odm\Interfaces\IEntity;

/**
 * Hydration is the act of populating an object from a set of data.
 */
interface IHydrator {

	public function extract(IEntity $object);

	public function hydrate(array $data, IEntity $object);
}