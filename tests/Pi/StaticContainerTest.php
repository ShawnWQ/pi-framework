<?hh

namespace Test;

use Pi\PhpUnitUtils,
	Pi\StaticContainer,
	Pi\Odm\MongoRepository,
	Pi\Common\RandomString,
	Mocks\BibleHost;

class StaticContainerRepository1 extends MongoRepository<StatiContainerDependency1> { }
class StatiContainerDependency1 {
	public function __construct(StatiContainerDependency2 $d2, StatiContainerDependency3 $d3) {}
}
class StatiContainerDependency2 {
	public function __construct(StatiContainerDependency4 $d4) {}
}
class StatiContainerDependency3 {
	public function __construct(StatiContainerDependency5 $d5) { }
}
class StatiContainerDependency4 { }
class StatiContainerDependency5 { }




class StaticContainerText extends BaseTest {
	
	protected $container;

	protected Set<string> $classes;

	public function setUp()
	{
		$this->container = new StaticContainer($this->buildCacheProvider(StaticContainer::class));
		$this->classes = Set{StatiContainerDependency1::class, StatiContainerDependency2::class, StatiContainerDependency3::class, StatiContainerDependency4::class, StatiContainerDependency5::class};
	}

	public function testGlboal()
	{
		$this->container->registerAutoWired(StatiContainerDependency4::class);
		$this->container->registerAutoWired(StatiContainerDependency5::class);
		$this->container->registerAutoWired(StatiContainerDependency2::class);
		$this->container->registerAutoWired(StatiContainerDependency3::class);
		$this->container->registerAutoWired(StatiContainerDependency1::class);
		$this->container->get(StatiContainerDependency1::class);
	}

	public function testCanRegisterAutoWired()
	{
		foreach ($this->classes as $className) { // classes are registered but not called, so not initialized during this 
			$this->container->registerAutoWired($className);
			$this->assertFalse($this->container->hasInstance($className));
			$this->assertTrue($this->container->hasRegistered($className));
		}

		// Get StatiContainerDependency5, remaining shouldn't be initialized
		$instance = $this->container->get(StatiContainerDependency5::class);
		$this->assertFalse($this->container->hasInstance(StatiContainerDependency3::class));
		$this->assertTrue($this->container->hasRegistered(StatiContainerDependency5::class));

		// Get StatiContainerDependency1, all remaining should be initialized
		$instance = $this->container->get(StatiContainerDependency1::class);
		foreach ($this->classes as $className) {
			$this->assertTrue($this->container->hasRegistered($className));
			$this->assertTrue($this->container->hasInstance($className));
		}
	}

	public function testCanRegisterAutoWiredAs()
	{
		$keys = array();
		foreach ($this->classes as $className) { // classes are registered but not called, so not initialized during this 
			$keys[$className] = $key = RandomString::generate();
			$this->container->registerAutoWiredAs($className, $key);
			$this->assertFalse($this->container->hasInstance($key));
			$this->assertTrue($this->container->hasRegistered($key));
			$this->assertFalse($this->container->hasInstance($className));
			$this->assertTrue($this->container->hasRegistered($className));
		}

		// Get StatiContainerDependency5, remaining shouldn't be initialized
		$instance = $this->container->get(StatiContainerDependency5::class);
		$this->assertTrue($this->container->hasInstance(StatiContainerDependency5::class));
		$this->assertFalse($this->container->hasInstance(StatiContainerDependency3::class));

		// Get StatiContainerDependency1, all remaining should be initialized
		$instance = $this->container->get(StatiContainerDependency1::class);
		foreach ($this->classes as $className) {
			$this->assertTrue($this->container->hasInstance($keys[$className]));
		}
	}

	public function testCanRegisterInstance()
	{
		$instance = new StatiContainerDependency5();
		$id = spl_object_hash($instance);
		$this->container->registerInstance($instance);
		$this->assertTrue($this->container->hasInstance(StatiContainerDependency5::class));
		
		$instanceObj = $this->container->get(StatiContainerDependency5::class);
		$this->assertEquals(spl_object_hash($instanceObj), spl_object_hash($instance));
	}

	public function testCanRegisterInstanceAsType()
	{
		$instance = new StatiContainerDependency5();
		$id = spl_object_hash($instance);
		$this->container->registerInstanceAs(StatiContainerDependency5::class, $instance);
		$this->container->registerAlias(StatiContainerDependency5::class, 'NewType');
		$this->assertTrue($this->container->hasInstance(StatiContainerDependency5::class));
		$this->assertTrue($this->container->hasInstance('NewType'))	;

		$instanceObj = $this->container->get('NewType');
		$this->assertEquals(spl_object_hash($instance), spl_object_hash($instanceObj));
	}


	public function testCanRegisterRepositoryType()
	{
		$host = new BibleHost();
		$container = $host->container;
		$container->registerRepository(StatiContainerDependency1::class, StaticContainerRepository1::class);
		$repository = $container->get(StaticContainerRepository1::class);
		$this->assertTrue($repository instanceof MongoRepository);
	}



	public function testCanGenerateHydratorsForDependencies()
	{
		foreach ($this->classes as $className) { // classes are registered but not called, so not initialized during this 
			$this->container->registerAutoWired($className);
		}
		$hydrator = $this->container->getHydrator(StatiContainerDependency3::class);
	}
}