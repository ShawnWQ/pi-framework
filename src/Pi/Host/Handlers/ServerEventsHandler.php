<?hh
namespace Pi\Host\Handlers;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;

class ServerEventsHandler extends AbstractPiHandler {

    public function processRequestAsync(IRequest $request, IResponse $response, string $operationName)
    {
      // create new subscription
      // register subscription in ServerEvents
    }
}
