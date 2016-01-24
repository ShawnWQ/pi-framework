<?hh

namespace Pi\Interfaces;

interface IHttpRequest  extends IRequest {

  public function httpResponse() : IHttpResponse;

  public function httpMethod() : string;

  public function headers() : Map<string,string>;

  public function parameters() : Map<string,string>;

  /**
   * The value of the X-Real-IP header, null if null or empty
   */
  public function xRealIp() : ?string;

  public function httpOrigin() : ?string;
}
