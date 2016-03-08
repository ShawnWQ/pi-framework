<?hh

namespace Pi\Interfaces;

interface IHttpResult {

  /**
   * Http Response status
   */
  public function status() : int;

  /**
   * HttpStatusCode
   */
  public function statusCode();

  public function statusDescription();

  public function contentType();

  public function response();

  public function requestContext() : IRequest;

  /**
   * The padding length written with the body, to be added to ContentLength of body
   */
  public function paddingLength();
}
