<?hh

namespace Pi\Interfaces\Host\Handlers;

use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;

interface IHttpAsyncHandler extends IHttpHandler {

	/**
	 * Initiates an asynchronous call to the HTTP handler.
	 * @var IRequest $context
	 * @var $asyncCallback The AsyncCallback to call when the asynchronous method call is complete. If cb is null, the delegate is not called.
	 * @var $dto the request dto
	 */
	public function beginProcessRequest(IRequest $context, $asyncCallback, $dto);

	/**
	 * Provides an asynchronous process End method when the process ends.
	 */
	public function endProcessRequest($asyncResult);
}