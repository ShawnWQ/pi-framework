<?hh

namespace Pi\Interfaces\Host\Handlers;

use Pi\Interfaces\IHttpRequest;

interface IHttpHandler {

	/**
	 * Gets a value indicating whether another request can use the IHttpHandler instance.
	 */
	public function isReusable() : bool;

	/**
	 * Enables processing of HTTP Web requests by a custom HttpHandler that implements the IHttpHandler interface.
	 */
	public function processRequest(IHttpRequest $context) : void;
}