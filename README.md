# Pi Services -

[![Build Status](http://codigo.ovh:8084/buildStatus/icon?job=f)](http://codigo.ovh:8084/job/f/)

This code is being actively developed. Feel free to join with me but dont open issues by now, as im writting on it everyday. It's unstable and simple not ready yet.

A Hacklang framework for micro services development inspired by ServiceStack and many other PHP projects like Doctrine, Slim, Eqrs. Also C# FluentValidation
I'm doing this not only for learning purposes. I've a few small sites running with this code and i want to dedicate my programming efforts on this.

The main goal is to provide a simple framework for micro services development.


ATM the chache mechanism is not working properly. When its ready, not only Services and Operations will be cached but also Validators, ODM mappings and other code using reflection to extract the entities mapping information.

### Writting a new application

Create a new AppHost class that extends an base AppHost (atm the only available is AppHost for http environments).

In AppHost you'll register the filters, plugins, custom providers (Logger, ServiceRunner, etc), error handlers and so on.

````
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
````



#### Plugins

- **CORS** - Request filter to handle CORS HTTP requests. For tests porpuses it's working but it will be validated. Also the allowed domains wouldn't be all but retrieved from cache.
- **FileSystem** - Services to upload files using the FileSystem. I also want to add a MongoDB provider with GridFS
- **Validation** - A validation feature to validate not only requests DTOs but also others objects. Requests are validated using a filter. Validations are added extending the PropertyValidator. Rules are defined with classes extending the AbstractValidator
- **UML Generator** - With this plugin i want to retrieve a UML like schema, indicating references beteween objects in Pi framework. It's all done with reflection and just for developing porpuses
- **Redis** - Redis Clients are created by managers. ATM i've only done the RedisLocalClientManager, the goal is to implement a pool size on clientes also
-**Auth** - Registration, Authentication, Recover and Login services



### New implementations

The goal is to implement new features and components with plugins. The debug logger is created with a factory but others logs will be available in a plugin distributed with this package.

The same example for OAuth providers, request/response serializers, application statics reporter, etc.

### Tests

Run the tests with phpunit.

### Roadmap

The current code i'm working on.

0.0.1
- AppHost - implementation of PiHost to HTTP requests
- Plugins implementation
- IdentityServices Basic Digest Authentication with email and password. Registration and password recovery by email
- Host Request/Response for http requests extending BasicRequest/Response,
- Service Meta with php cache on a json file. Cache is stored at ServiceMetadata, implementations in ServiceMeta with ServiceMetaValue keeped at ServiceController
- Provide a global HostContext to access resolver, the current application, database factory, debug factory, etc. Accesses to this object always assert that the PiHost was already initialized.
- Filters - access to request, response and DTO. can change both
- Mongo Logging Plugin provider to store logs in mongodb. Types are saved as collections if otherwise defined in configuration
- Event library with a manager, subscriber and default arguments. Available from top objects
- Validation library aimed to validated not only requests but also dtos. Rules are defined with attributes.
- ODM: COPY THE DOCTRINE ODM LICENSE! I'm writting the ODM looking up at Doctrine implementation. Same for Mono implementation of System.Data

0.0.2
- Redis implementation for IMessageFactory.
- Service Discovery -  Services will discover and register with redis, also used for pub/sub for the services operations acting as a message broker.
- Validation implement a mapper to define users. Until now only available with attributes
- OAuth2 Plugin authentication

0.1
- RabbitMq implementation for IMessageFactory.
