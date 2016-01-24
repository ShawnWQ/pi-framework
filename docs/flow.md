## Architecture - sketch

I'm trying to implement the comunication with messages. The default IMessageService for PiHost is the InMemoryService wich runs services locally only.

To scale the services i'll implement QueueMessageService implementations for Redis and Rabbit. When running this services, the application publish the messages to the message queues. It's provided publish, execute and executeOneWay.

Service scalling provides publish and subscription from thirds like Redis allowing PiHost to scale in nodes. Each node is composed by two or more servers running a PiHost

First PiHost in node assumes as master.
Messages are delegated from MessageService (the redis pub/sub) and each PiHost execute the message. Database replication is provided from another layer at mongodb or mysql with master and slave. The same should go for FileSystem, and own plugin for that. each AppHost

When a message is published by the PiHost first executing server executing the message, this server informs all subscribed AppHost from the node from the event and they execute the message with the same request context.
to allow features like file storage amoung the servers, the services it self should subscribe to those events and copy the data from the server that executed the message

each PiHost has an open socket listening for Redis (example) events to allow subscribed requests from this ApplicationHost to be called from others nodes.


## Pi framework
The core.

### Example Services and requests provided by them
- NewsletterService - create, read, find
- OAuthService - provide OAuth authentication
- BasicLoginService - authentication with username and password
- FileService - store files with unique ids and serve the files as well


### PiHost - gateway class
-ServiceController
-ServiceRoute - object with all Route objects
-Plugins - plugins applications inhering an PiHost itself. the services from the plugins are registered at the ServiceController. all objects like ServiceRunner are the same from the base PiHost (injected to plugins)
- create service runner - when the applicaction is configured, the route resolved from the request is translated to a message and delegated to a new ServiceRunner

### ServiceController - the service register
- RegisterServiceExecutor
- RegisterService - register ServiceMeta and restPath (to resolve route -> requestType)
- get service - create a new service executor and return a new ServiceExecuteFn (where registered at RegisterService)

### ServiceMeta
save meta data information to generate webservices

#### ServiceRunner
object that runs the services
- handlers for before/after
- execute messsage with application service.
- execute message one way with application services without waiting for response
- publish message to IMessageService. subscribers are notified and execute (the MessageService shouldn't call the server instance in cluster node that is publishing the message)
- subscribe to an event (message or not, having and id)

consume service executor to execute a method

### ServiceExecutor
- create ServiceExecuteFn passing service type, request type with IRequest interface and method info

the handler function receive the IRequest, creates the service instance, call the method passing the request and get response

### ServiceExecuteFn
the object that get the service from ServiceController and execute the method passing the reqeuest

- add handlers for onBegin/EndCallback
- invoke method: call begin, handler, end

when the handler is invoked, the IResponse from the service method is returned.

### MessageService
provide publish/subscribe pattern to events. messages executed dont notify subscribers
in case of message queues implementations from thirds, and socket is openened by the service. InMemoryService dont
services are registered in ServiceController. from there you get the handler to call a specific message


### InMemoryService
default IMessageService used. queues are static and not mean to scale
* services nodes are registered with ICacheProvider

RedisMessageService/RabbitMesageService - scalling services

## Pi Common
Dependencies for all Pi Libraries (including the Pi main core)

## Pi Redis

## RedisClientManager
connection pool factories to acessing RedisClient (are singleton and registered at PiHost Container):
- blockin when the pool is reached
- executed and disposed outside pool

### RedisClient
the redis commands api constructed from RedisClientManager
