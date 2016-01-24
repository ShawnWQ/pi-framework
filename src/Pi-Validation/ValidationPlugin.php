<?hh

namespace Pi\Validation;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Filters\RequestFilter;
use Pi\Host\HostProvider;


class ValidationPlugin implements IPreInitPlugin {

	public function configure(IPiHost $appHost) : void
	{
		$appHost->addRequestFiltersClasses(new ValidationAssertionFilter());
	}
}
