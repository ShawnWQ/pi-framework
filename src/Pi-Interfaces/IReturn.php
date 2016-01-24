<?hh

namespace Pi\Interfaces;

/**
 * Interface implemented by request DTOs to indicate the response type
 * This is a temporary fix. The response type should be fully namespaced class name
 */
interface IReturn {

  public function getResponseType() : string;
}
