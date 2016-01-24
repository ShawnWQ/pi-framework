<?hh

namespace Pi\Client;
use Pi\Interfaces\IServiceClient;
use Pi\Interfaces\IMessageProducer;

abstract class ServiceClientBase
  implements IServiceClient, IMessageProducer {

    public abstract function send(TRequest $request);
  }
