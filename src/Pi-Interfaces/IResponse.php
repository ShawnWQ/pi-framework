<?hh

namespace Pi\Interfaces;

interface IResponse extends IContainable {

	public function close() : void;

	public function isClosed() : bool;

	public function getStatusCode() : int;

	public function setStatusCode(int $code) : void;

	public function getStatusDescription() : string;

	public function setStatusDescription(string $desc) : void;

	public function endRequest($skipHeaders = true) : void;

	public function write($text, int $responseStatus = 200) : void;

	public function writeDto(IRequest $httpRequest, $dto) : void;

	public function setHeaders();

	public function headers() : Map<string,string>;

	public function cookies() : Map<string,string>;
}
