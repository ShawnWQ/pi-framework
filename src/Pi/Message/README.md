IMessageFactory
 -createMessageProducer

IMessageQueueClientFactory
- createMessageQueueClient

IMessageProducer
 - publish($type, $messageBody);
 - publishMessage($type, IMessage $message);


IMessageQueueClient LocalMessageQueueClient(MessageQueueClientFactory)
 - ack()
 - createMessage($type)
 - get($type);
 - getAsync($type);
 - nak();
 - notify();
 - publish();

MessageQueue

IMessageService
 - getStats();
 - getStatsDescription();
 - getStatus();
 - registerHandler($handler); // MessageHandler per request
 - start();
 - stop();
