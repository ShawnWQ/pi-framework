<?hh

namespace Pi\Interfaces;

use Pi\Interfaces\IMessage;

interface IMessageProducer {

  public function publish($type, $messageBody);

  public function publishMessage($type, IMessage $message);
  
}
