<?hh

namespace Pi\Common\Mapping;

use Pi\Common\Mapping\HydratorPoviderBase,
	Pi\HydratorFactoryBase,
	Pi\Interfaces\HydratorFactoryInterface,
	Pi\Common\Mapping\AbstractHydratorFactory,
	Pi\Comm\Mapping\HydratorAutoGenerate;




class StaticHydrator implements HydratorFactoryInterface {
	
	public function __construct(
    	protected string $hydratorPath,
    	protected string $hydratorNamespace,
    	?HydratorAutoGenerate $autoGenerate = null)
	{
	}

	use HydratorPoviderBase;

	public function hydrate($document, $data)
	{

	}

	public function getHydratorPath()
	{
		return $this->hydratorPath;
	}
	public function getHydratorNamespace()
	{
		return $this->namespace;
	}
}