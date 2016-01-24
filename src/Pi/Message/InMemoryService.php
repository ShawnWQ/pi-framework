<?hh
namespace Pi\Message;

use Pi\Interfaces\IMessageFactory;
use Pi\Interfaces\IMessageService;
use Pi\Host\BasicRequest;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IHasAppHost;
use Pi\Interfaces\IPiHost;
use Pi\Message\MessageHandlerFactory;

/**
 * The default message service used by InMemoryFactor
 * A Redis message provider would call the RedisClient instead of creating an RedisMessageService as the responsability is from the Producer
 * Acess for producers (ie: Services like UserService, NewsService) and MqCle
 */
class InMemoryService implements IMessageService, IContainable, IHasAppHost {

    protected $subs = Map{};
    protected $producer;
    protected $appHost;
    protected $running = false;
    protected $handlers = Map{};
    protected $container;

    public function __construct(protected InMemoryFactory $factory)
    {

    }

    public function appHost()
    {
      return $this->appHost;
    }
    public function setAppHost(IPiHost $host)
    {
      $this->appHost = $host;
    }


    public function ioc(IContainer $ioc){
      $this->container = $ioc;
    }

    /**
     * Shortcut to create a Message Queue client from the service message factory
     * @return [type] [description]
     */
    public function producer()
    {
      if(is_null($this->producer))
      {
        $this->producer = $this->createMessageProducer();
      }
      return $this->producer;
    }

    private function assertServiceIsRunning()
    {
      if(!$this->running){
        throw new \Exception('The InMemoryService should be started');
      }
    }

    public function execute($message)
    {
      $requestDto = $message->body();
      $request = new BasicRequest($requestDto);
      return $this->appHost->serviceController()->execute($requestDto,$request);
    }

    public function publish($request)
    {

      $type = get_class($request);
      //$this->assertServiceIsRunning();
      $responses = array();

      foreach($this->handlers as $key => $handlerFactory){

        if($key == $type) {
          $handler = $handlerFactory->createMessageHandler();

          $client = $this->createMessageQueueClient();

          $response = $handler->process($client);

          foreach($this->subs as $ids){

            foreach($ids as $id => $fn) {
              $fn($response);
            }
          }

          $responses[] = $response;
        }
      }

      switch(count($responses)){
        case 0:
        throw new \Exception('No handler found');

        case 1:
          return $responses[0];

        default:
        return $responses;

      }
    }

    public function subscribe($type, $handler)
    {
      //$this->assertServiceIsRunning();
      $id = spl_object_hash($handler);
      if(!$this->subs->contains($type))
      {
        $this->subs[$type] = array();
      }
      $this->subs[$type][$id] = $handler;
    }




    public function registerHandler($processMessage, $processEx = null)
    {
      if(is_null($processEx)){
        // set default
      }
      $message = MessageFactory::create($processMessage);
      $factory = $this->createFactory($message, $processEx);

      $type = get_class($processMessage);

      $this->handlers[$type] = $factory;
    }

    /**
     * Start the MQ Host
     * Called by application host
     * @return [type] [description]
     */
    public function start()
    {
      if($this->running){
        return;
      }


    }

    /**
     * Stop the MQ host
     * @return [type] [description]
     */
    public function stop()
    {

    }

    public function getStatus()
    {

    }

    public function getStats()
    {

    }

    public function getStatsDescription()
    {

    }
    /**
     * The factory for this Service
     * Use it to create producers and consumers
     */
    public function messageFactory() : IMessageFactory
    {
      return $this->factory;
    }

    /**
     * Shortcut to create a Message Queue client from the service message factory
     */
    public function createMessageQueueClient()
    {
      return $this->factory->createMessageQueueClient();
    }

    public function createMessageProducer()
    {
      return $this->factory->createMessageProducer();
    }

    private function createFactory($process, $processEx)
    {
      // create new message handler factory
      return new MessageHandlerFactory($this, $process, $processEx);
    }
}
