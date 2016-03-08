<?hh

namespace Pi\Validation;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IPlugin;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Filters\RequestFilter;
use Pi\Host\HostProvider;


class ValidationPlugin implements IPlugin {

	public function register(IPiHost $appHost) : void
	{
		$appHost->addRequestFiltersClasses(new ValidationAssertionFilter());
	}
}
