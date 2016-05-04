<?hh

namespace Pi\Odm;

use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;


class DocumentRepair extends AbstractEntityRepair implements IContainable {
	
	public function __construct(
		protected UnitWork $unitWork)
	{

	}

	public function ioc(IContainer $ioc)
	{

	}

	public function repair(string $className, ?array $fields = null)
	{
		if(is_null($fields)) {
			$class = $this->unitWork->mongoManager()->getClassMetadata($className);
			if(is_null($class))
				throw new \Exception(sprintf('Class %s wasnt registered', $className));
		}
		$query = $this->unitWork->queryBuilder($className)
			->update()
			->multi()
			->upsert();

		foreach ($fields as $field) {
			$query->field($field)->setDefault();
		}
	}

	protected function getCollection(string $entityClass)
	{

	}
}