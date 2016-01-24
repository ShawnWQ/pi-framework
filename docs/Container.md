## Container

@TODO implement the container meta data for cache :)

### Register global objects - slower, defined with attributes

### Register Services

### Register IContainable 


## Dependency Injection

Dependency injection is provided from differentes ways, from the faster to the slower

### The constructor

The better example is a Service. The Service is an object that's reused and constructed just once.

The Container can auto wire the Service dependencies using reflection just once per class. I read the dependencies from the constructor method params.


````
class BibleService {
	public function __construct(
			public IDbDatabase $database,
			public IRedisManager $redisManager,
			public FileSystem $fileSystem) {

	}
}

$container->register(new BibleService());


### Attribute

<<Inject('BibleService'>>
public $bibleService;

If the attribute value isn't provided, the dependency alias is resolved from the property.