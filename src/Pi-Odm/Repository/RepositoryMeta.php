<?hh

namespace Pi\Odm\Repository;

use Pi\Interfaces\IEntity;

class RepositoryMeta {
	
	public function __construct(
		protected string $className,
		protected array $dependencies = array()
	)
	{

	}
}