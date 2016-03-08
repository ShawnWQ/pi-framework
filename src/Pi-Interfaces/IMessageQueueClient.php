<?hh

namespace Pi\Interfaces;

interface IMessageQueueClient {

  //Acknowledge the message was received
  //public function ack();

  public function createMessage($type);

  public function get($type);

  public function getAsync($type);

  //public function nak();

  //public function notify();

  //public function publish();
}
