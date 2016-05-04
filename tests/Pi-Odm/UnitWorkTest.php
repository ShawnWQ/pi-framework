<?hh
use Mocks\MultiTenantHostMock;
use Mocks\OdmContainer;
use Mocks\MockEntity;
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\Query\UpdateQueryBuilder;

class UnitWorkTest extends \PHPUnit_Framework_TestCase{

  protected $mongoManager;

  protected $container;

  public function setUp()
  {
      $container = OdmContainer::get();
      $this->unitWork = $container->get('UnitWork');
      $this->mongoManager = $container->get('MongoManager');
      $this->container = $container;
  }

  public function testDocumentIsSchedule()
  {
    $class = $this->mockClass();
    $entity = new MockEntity();
    $this->assertFalse($this->unitWork->isDocumentScheduled($entity));
    $this->unitWork->scheduleForInsert($class, $entity);
    $this->assertTrue($this->unitWork->isDocumentScheduled($entity));
  }

  public function testDocumentUpdatedCantBeScheduleForInsert()
  {
    $class = $this->mockClass();
    $entity = new MockEntity();
    $this->unitWork->scheduleForUpdate($entity);
    $throwed = false;
    try {
      $this->unitWork->scheduleForInsert($class, $entity);
    }
    catch(\ArgumentException $ex) {
      $throwed = true;
    }
    $this->assertTrue($throwed);
  }

  public function testDocumentRemovedCantBeScheduleForInsert()
  {
    
  }

  public function testDocumentAlreadyRegisteredCantBeScheduleForInsert()
  {
    
  }

  public function testInsertionsArePreparedWithMultiTenant()
  {
    $host = new MultiTenantHostMock();
    $host->init();
    $this->unitWork = $host->tryResolve('UnitWork');
    $this->mongoManager = $host->tryResolve('MongoManager');

    $class = $this->mockClass();
    $this->container->get('OdmConfiguration')->setMultiTenantMode(true);
    $entity = new MockEntity();
    $this->unitWork->persist($entity);
    $this->unitWork->commit();
  }

  public function testCreateQueryBuilder()
  {
    $entity = new MockEntity();
    $builder = $this->unitWork->createQueryBuilder(get_class($entity));
    $this->assertTrue($builder instanceof UpdateQueryBuilder);
  }

  public function testScheduleDocumentForInsert()
  {
    $class = $this->mockClass();
    $entity = new MockEntity();
    $this->assertFalse($this->unitWork->isScheduledForInsert($entity));
    $this->unitWork->scheduleForInsert($class, $entity);
    $this->assertTrue($this->unitWork->isScheduledForInsert($entity));
  }

  private function mockClass()
  {
    return $this->mongoManager->getClassMetadata('Mocks\MockEntity');
  }

}
