<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessageHandlerFactory;
use Pi\Interfaces\IMessageHandler;
use Pi\Interfaces\IMessageService;
use Pi\Message\MessageBaseService;
use Pi\Message\MessageHandler;

class MessageHandlerFactory
  implements IMessageHandlerFactory {

    public function __construct(protected IMessageService $service, protected $process, protected $processEx)
    {

    }
    public function createMessageHandler() : IMessageHandler
    {
        $handler = new MessageHandler($this->service, $this->process);
        return $handler;
    }
}
