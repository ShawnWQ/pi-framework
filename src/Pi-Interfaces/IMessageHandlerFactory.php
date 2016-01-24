<?hh

namespace Pi\Interfaces;

interface IMessageHandlerFactory {

  public function createMessageHandler() : IMessageHandler;
}
