<?hh

namespace Pi\Validation;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IPreInitPlugin;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Filters\RequestFilter;
use Pi\Host\HostProvider;
use Pi\HttpResult;
use Pi\Extensions;

/**
 * Validation is executed using a Filter instead of callbacks in AppHost
 */
class ValidationAssertionFilter extends RequestFilter {
	
	public function execute(IRequest $req, IResponse $res, $requestDto) : void
	{
		$validator = $this->appHost->getValidator($requestDto);
		
		if($validator === null) {
			return;
		}
		Extensions::assertValidation($validator, $requestDto);
	}
}