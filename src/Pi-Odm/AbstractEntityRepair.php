<?hh

namespace Pi\Odm;

abstract class AbstractEntityRepair {
	abstract function repair(string $className, array $fields);
}