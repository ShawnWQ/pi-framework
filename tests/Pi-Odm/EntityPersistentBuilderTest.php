<?hh

use Mocks\OdmContainer;
use Pi\Odm\EntityPersistentBuilder;
use Mocks\MockEntity4;
use Mocks\EntityInherited;



class EntityPersistentBuilderTest extends \PHPUnit_Framework_TestCase {

	protected $mongoManager;

	protected $unitWork;

	protected $builder;

	public function setUp()
	{
		$container = OdmContainer::get();
  	$this->unitWork = $container->get('UnitWork');
  	$this->mongoManager = $container->get('MongoManager');
  	$this->builder = new EntityPersistentBuilder($this->mongoManager, $this->unitWork);
	}

	public function testCanCreateBuilder()
	{
		$this->assertFalse(is_null($this->builder));
	}

	/*
	 * EntityPersistent gets the array to send to mongodb from builder
	 * Builder is responsible for constructing the insertion
	 */
	public function testPrepareInsertData()
	{
		$doc = new MockEntity4();
		$doc->address('value');
	/*	$insertion = $this->builder->prepareInsertData($doc);
		$this->assertTrue(array_key_exists('address', $insertion));
		$this->assertFalse(array_key_exists('name', $insertion));*/
	}

	public function testPrepareInsertDataWithInheritance()
	{
		$doc = new EntityInherited();
		$insertion = $this->builder->prepareInsertData($doc);
		$this->assertEquals($insertion['type'], get_class($doc));
	}

	public function testPrepareInsertDataWithInheritanceWithoutOverwritingExplicity()
	{
		$doc = new EntityInherited();
		$doc->setType('new-type');
		$insertion = $this->builder->prepareInsertData($doc);
		$this->assertEquals($insertion['type'], 'new-type');
	}
}
