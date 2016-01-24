<?hh

namespace Pi\Interfaces;

interface IResponse extends IContainable {

  public function writeDto(IRequest $httpRequest, $dto) : void;

  public function close() : void;

  public function isClosed() : bool;

  public function getStatusCode() : int;

  public function setStatusCode(int $code) : void;

  public function getStatusDescription() : string;

  public function setStatusDescription(string $desc) : void;
}
