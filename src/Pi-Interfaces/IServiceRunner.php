<?hh

namespace Pi\Interfaces;

interface IServiceRunner<TRequest>
  extends IMessageProducer {
    public function onBeforeExecute(IRequest $requestContext, TRequest $request);
      public function onAfterExecute(IRequest $requestContext, $response);
      public function handleException(IRequest $request, TRequest $requestDto, $ex);

      public function executeMessage(IRequest $requestContext, $instance, IMessage $request);
      public function execute(IRequest $requestContext, $instance, TRequest $requestDto);
      public function executeOneWay(IRequest $requestContext, $instance, TRequest $request);
}
