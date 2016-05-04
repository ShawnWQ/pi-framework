<?hh

namespace Pi;

use Pi\HydratorFactoryBase,
	Pi\Interfaces\HydratorFactoryInterface,
	Pi\Common\Mapping\AbstractHydratorFactory,
	Pi\Comm\Mapping\HydratorAutoGenerate;




trait HydratorPoviderBase {
	
	require extends AbstractHydratorProvider;

	parent::__construct($this->getHydratorPath(), $this->getHydratorNamespace());

	abstract function getHydratorPath();

	abstract function getHydratorNamespace();
}
