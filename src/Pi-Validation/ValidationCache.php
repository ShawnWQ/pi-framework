<?hh

namespace Pi\Validation;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class ValidationCache implements IContainable{

	protected $ioc;

	public function ioc(IContainer $container)
	{
		$this->ioc = $container;
	}

	public function get($instance) : ?AbstractValidator
	{
		return $this->ioc->getValidator(get_class($instance));
	}

	public function register($instance, AbstractValidator $validatorInstance)
	{
		$this->ioc->registerValidator(get_class($validatorInstance), function(IContainer $ioc) use($validatorInstance){
			return $validatorInstance;
		});
	}
}