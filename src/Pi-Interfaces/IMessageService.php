<?
/**
 * Implementations for MessageService: InMemory, Redis, RabbitMq
 *
 * @example
 * Register the message service in AppHost container
 * $container->register<IMessageService>(new RabbitMqService());
 * $server = $container->resolve<IMessageService>();
 * $server->registerHandler<AuthOnly>($m => function(){
*            var req = new BasicRequest { Verb = HttpMethods.Post };
            *req.Headers["X-ss-id"] = $m.GetBody().SessionId;
            *var response = ServiceController.ExecuteMessage($m, req);
            *return response;
 * });
 *
 */

namespace Pi\Interfaces;

interface IMessageService {

  public function getStats();

  public function getStatsDescription();

  public function getStatus();

  public function registerHandler($handler);

  public function start();

  public function stop();
}
