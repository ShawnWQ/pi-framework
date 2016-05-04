# Pi Framework

This code is being actively developed. Feel free to join with me but dont open issues by now, as im writting on it everyday. It's unstable and simple not ready yet.

A Hacklang framework for micro services development inspired by ServiceStack and many other PHP projects like Doctrine, Slim, Eqrs. Also C# FluentValidation
I'm doing this not only for learning purposes. I've a few small sites running with this code and i want to dedicate my programming efforts on this.

The main goal is to provide a simple framework for micro services development.

## Understanding the Framework

Pi isn't a lighweight super fast performance. Instead is a framework designed with OOO and following principles that easy the refactoring.

[PiHost](src/Pi/PiHost.php) is the application host responsible for handling all top level objects like Plugins, Hooks, Services.

With HHVM, each request doesn't open a new process: instead the engine handles a process with N threads. Pi relies on dependency injection, metadata factories and heavy cached files. 

Pi has 3 major life cycles: **configure**, **build** and **init**

It's important to understand the framework life cycles if you inteand to help me developing it.

+ Register -registration of object in some factory who is responsible for making him. Register don't necessary envolve an object instance
+ Invoke - execute class method that depend on some life cycle/event. Invoke can be executed more than once.
+ Run - invoke class method. Differs from invoke where run executs an specific request/method, while invoke is related to the event/life cycle.

### Configure

The configuration of PiHost. 
+ Register Services
+ Register Plugin
+ Register Filters/Hooks
+ Register Providers implementations
..+ ISerializerService
..+ ClassMappingDriver
..+ ClassMetadataFactory
+ Register Catch all handlers
+ Register Exception Handlers
+ Register AppSettingsProviderInterface
+ Run Pre Init Plugin Configuration IPlugin->configure(PiHost)
+ Run Plugin Registration IPlugin->register(PiHost)
+ Register Service Metadata
+ Register Hydrators autoloader

To change a HTTP request header, use the CacheProvider or others high level dependencies the developer must use appropiated features like Plugins and Hooks. This allow to execute code on all application life cycles.

Configuration is meant to instruct the framework how dependencies/configuration should be done, and not execute any code.

When the application **configure** is invoked, the PiHost has already had construct some dependencies like the **CacheProvider** and **SerializerService**. It's why the application need the build phase, to know what final implementations are used to avoid unnecessary objects initialization.


### Build

Build is executed after the configuration of the application. 

The build of PiHost:
+ Assert hydration requirements
+ Cache Container implementations
+ Cache registered Services
+ Cache Metadata Factories operations (ServiceMetada, OdmMetadata)
+ Cache Routes/Operations map

Each Object in Container implementing [BuildInterface](https://github.com/guilhermegeek/communia/blob/master/src/Pi-Interfaces/BuildInterface.php) is then invoked with **->build(PiHost)** after the application is built.

When the application is built successfully it generated the entry point for webservers (by default index.php).

An example:
```php
$app = new YourApp();
$app->init();
```


### Initialize

The initialize phase is invoke per HTTP request. At this life cycle, the application trust that cache is up to date and the build was executed succefully.


## Writting a new application

Create a new AppHost class that extends an base AppHost (atm the only available is AppHost for http environments).

In AppHost you'll register the filters, plugins, custom providers (Logger, ServiceRunner, etc), error handlers and so on.

```php
use Pi\Service;
use Pi\Odm\MongoDB\Repository;
use Pi\AppHost;

class PostNewsletter {

  protected $firstName;

  <<Validation("String"),MinLength(5)>>
  public function firstName($value = null)
  {
    if($value === null) return $this->firstName;
  }
}

class PostNewsletterResponse extends \Pi\Response {

}

class NewsletterRepository extends MongoRepository {

}
class NewsletterService extends Service {

  public PostNewsletterValidator $validator;

  public NewsletterRepository $newsletterRepository;

  <<Request,Route('/test'),Auth>>
  public function post(PostNewsletter $request){
    $currentUser = $this->request()->author();
    $this->newsletterRepository->add(new Newsletter($request->getFirstName())});
    $this->newsletterRepository->flush();
    return new PostNewsletterResponse();
  }
}

class TestAppHost
  extends AppHost {

    public function configure(Container $configure)
    {
      $this->registerService(new NewsletterService());
      $this->preRequestFilters->add(function(IRequest $req, IResponse $res, $dto) {
        $req->headers()->set('New-Header', 'Value');
      });
    }
  }

class TestPlugin implements IPlugin {

  public function configure(IPiHost $host)
  {
    $host->registerPreRequestFilter(new CustomPreRequestFilter());
  }
}

class CustomPreRequestFilter extemds PreRequestFilter {

  public function execute(IRequest $httpRequest, IResponse $httpResponse, $requestDto) : void;
  {

  }
}

$host = new TestAppHost();
$host->init();
```

## Plugins

- **CORS** - Request filter to handle CORS HTTP requests. For tests porpuses it's working but it will be validated. Also the allowed domains wouldn't be all but retrieved from cache.
- **FileSystem** - Services to upload files using the FileSystem. I also want to add a MongoDB provider with GridFS
- **Validation** - A validation feature to validate not only requests DTOs but also others objects. Requests are validated using a filter. Validations are added extending the PropertyValidator. Rules are defined with classes extending the AbstractValidator
- **UML Generator** - With this plugin i want to retrieve a UML like schema, indicating references beteween objects in Pi framework. It's all done with reflection and just for developing porpuses
- **Redis** - Redis Clients are created by managers. ATM i've only done the RedisLocalClientManager, the goal is to implement a pool size on clientes also
-**Auth** - Registration, Authentication, Recover and Login services