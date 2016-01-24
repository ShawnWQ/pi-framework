<?hh

namespace Pi\Interfaces\Host\Handlers;

use Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IHttpResponse;

interface IHttpAsyncHandler extends IHttpHandler {

	/**
	 * Initiates an asynchronous call to the HTTP handler.
	 * @var IHttpRequest $context
	 * @var $asyncCallback The AsyncCallback to call when the asynchronous method call is complete. If cb is null, the delegate is not called.
	 * @var $dto the request dto
	 */
	public function beginProcessRequest(IHttpRequest $context, $asyncCallback, $dto);

	/**
	 * Provides an asynchronous process End method when the process ends.
	 */
	public function endProcessRequest($asyncResult);
}