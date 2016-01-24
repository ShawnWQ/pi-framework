<?hh

use Mocks\MongoOdmConfiguration;
use Mocks\MockOdmConfiguration;
use Mocks\OdmContainer;
use Mocks\MockEntity;
use Pi\Odm\UnitWork;
use Pi\PiContainer;
use Pi\Interfaces\IContainer;
use Pi\EventManager;
use Pi\Host\HostEvents;
use Pi\Host\OperationHydratorFactory;
use Pi\Host\OperationMetaFactory;
use Pi\Host\OperationDriver;
use Pi\MongoConnection;
use Pi\Odm\Mapping\Driver\AttributeDriver;
use Pi\Odm\EntityMetaDataFactory;
use Mocks\MockHostProvider;
use Mocks\VerseCreateRequest;

class OperationHydratorFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testCanGetHydratorForClassAndHydrate()
	{
		$entity = new VerseCreateRequest();
		$factory = $this->createFactory();

		$factory->hydrate($entity, array('id' => 1, 'book' => 'Jesus'));
		$this->assertEquals($entity->getBook(), 'Jesus');
	}

	public function testDispatchPostHydrationEvent()
	{
		$factory = $this->createFactory();
		$em = MockHostProvider::instance()->container()->get('EventManager');
		$invoked = false;
		$em->add(array(HostEvents::PostGenerateHydrator), function(PostPostGenerateHydratorArgs $req) use($invoked){
			$invoked = true;
		});

		$req = new VerseCreateRequest();
		$factory->hydrate($req, array('id' => 1, 'book' => 'Jesus'));
		$this->assertTrue($invoked);
	}

	public function testCanCacheHydratorProperly()
	{
		$entity = new VerseCreateRequest();
		$factory = $this->createFactory();
		$factory->hydrate($entity, array('id' => 1, 'book' => 'Jesus'));

	}

	protected function createFactory()
	{
		$host = new \Mocks\BibleHost();
	    $host->init();
	    $em = MockHostProvider::instance()->container()->get('EventManager');
	    $driver = OperationDriver::create(array('../'), $host->container->get('EventManager'), $host->cacheProvider());
	    $factory = new OperationMetaFactory($em, $driver);
	    
	    return new OperationHydratorFactory(
	    	$host->config(),
		 	$factory,
		 	$host->container()->get('EventManager'),
		 	$host->ServiceController()
		);
	}
}
