<?hh

namespace Pi\Interfaces;

use Pi\Interfaces\IResponse;

interface IHttpResponse extends IResponse {

	public function endRequest($skipHeaders = true) : void;

	public function write($text, int $responseStatus = 200) : void;

	public function writeDto(IRequest $httpRequest, $dto) : void;

	public function setHeaders();

	public function headers() : Map<string,string>;

	public function cookies() : Map<string,string>;
}
