<?hh

namespace Pi\Interfaces;


interface IMessage {

  /**
   * The Request DTO is embeded in Body
   */
  public function body();
  /**
   * Date when the message was created
   */
  public function createdDate();

  public function error();

  public function options();

  /**
   * Queue priority
   */
  public function priority();

  public function replyId();

  public function replyTo();

  /**
   * Number of reply attempts
   */
  public function replyAttempts();

  public function tag();
}
