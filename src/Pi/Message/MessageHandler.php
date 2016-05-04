<?hh

namespace Pi\Message;

use Pi\Interfaces\IMessageHandler;
use Pi\Interfaces\IMessageService;
use Pi\Interfaces\IMessageFactory;
use Pi\Interfaces\IMessageQueueClient;

/**
 * Message Handler
 */
class MessageHandler
  implements IMessageHandler {

    protected $log;

    protected $processException;

    protected $replyClientFactory;

    protected $processed;

    protected $failed;

    protected $retries;

    protected $receivedNormal;

    protected $priorityReceived;

    protected $outReceived;

    /**
     * ?\DateTime
     */
    protected $lastProcessed;

    protected $processQueueNames = array();

    protected $queueClient;

    /**
     * [__construct description]
     * @param IMessageService $service        Service to run the operations
     * @param IMessage|mixed $processMessage Message or request/command
     * @param integer   $retryCount     retry attemps. 2 = 3 at all (1 + 2 retries)
     */
    public function __construct(protected IMessageService $messageService, protected $processMessage, protected $retryCount = 2)
    {
      $this->processQueueNames[] = 'main';
    }

    public function getMqClient()
    {
      return $this->messageService->createMessageClient();
    }

    public function messageType()
    {
      return get_class($this->processMessage);
    }

    public function process(IMessageQueueClient $client)
    {
      foreach($this->processQueueNames as $name){
        // execute on all queues

        $response = $this->messageService->execute($this->processMessage);
        return $response;
      }
    }

    public function processQueue(IMessageQueueClient $client, string $queueName, $next = null)
    {

    }

    public function getStats()
    {
      // Create a new MessageHandlerStats with the totals vars
    }

    public function processMessage(IMessageQueueClient $client, $message)
    {
        // get response
    }

    public function dispose()
    {

    }

    public function getMessageType()
    {
      return get_class($this->processMessage);
    }
  }
