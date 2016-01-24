<?hh
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\Query\QueryType;
use Pi\Odm\Query\QueryExecutor;
use Pi\Odm\MongoManager;
use Mocks\OdmContainer;
use Mocks\MockEntity;

class QueryExecutorTest extends \PHPUnit_Framework_TestCase {

  protected $container;


  protected $mongoManager;

  protected $unitWork;

  public function setUp()
  {
    $this->container = OdmContainer::get();
    $this->mongoManager = $this->container->get('MongoManager');
    $this->unitWork = $this->container->get('UnitWork');
  }

  public function testCreate()
  {
    $entity = new MockEntity();
    $this->assertNull($entity->id());
    $entity->name('test');

    $this->unitWork->persist($entity);
    $this->unitWork->commit();

    $this->assertNotNull($entity->id());
  }

  public function testCanFindAndUpdate()
  {
    $entity = new MockEntity();
    $entity->counter(4);
    $this->unitWork->persist($entity);
    $this->unitWork->commit();

    $builder = new QueryBuilder($this->unitWork, $this->mongoManager, get_class($entity));
    $result = $builder
      ->findAndUpdate()
      ->hydrate()
      ->field('_id')->eq($entity->id())
      ->field('counter')->set(10)
      ->getQuery()
      ->getSingleResult();

    $this->assertTrue(!is_null($result));
    $this->assertTrue($result instanceof MockEntity);
  }

  public function testCanCount()
  {
      $entity = new MockEntity();
      $entity->name('asd');
      $this->unitWork->persist($entity);
      $this->unitWork->commit();

      $builder = new QueryBuilder($this->unitWork, $this->mongoManager, get_class($entity));

      $result = $builder
          ->field('name')->eq('asd')
          ->getQuery()
          ->count();
      $this->assertTrue($result >= 1);
  }

  public function testCreateFindOneByFieldEq()
  {
    $entity = new MockEntity();
    $entity->name('asd');

    $this->unitWork->persist($entity);
    $this->unitWork->commit();

    $builder = new QueryBuilder($this->unitWork, $this->mongoManager, get_class($entity));
    $r = $builder->hydrate()
      ->field('name')->eq('asd')
      ->find()
      ->getQuery()
      ->execute();
    $this->assertTrue($r[0] instanceof MockEntity);

    $r = $builder->hydrate()
      ->field('name')->eq('asd')
      ->find()
      ->getQuery()
      ->getSingleResult();

    $this->assertTrue($r instanceof MockEntity);

    // No query filters
    $r = $builder->hydrate()
      ->find()
      ->getQuery()
      ->execute();
    $this->assertTrue($r[0] instanceof MockEntity);
  }

  public function testCanPushElements()
  {
    $doc = new MockEntity();
    $doc->tokens(array());
    $doc->name('Gui');
    $this->unitWork->persist($doc);
    $this->unitWork->commit();

    $builder = $this->createBuilder();
    $r = $builder
        ->update()
        ->field('_id')->eq($doc->id())
        ->field('tokens')->push('Idiozincrecia')
        ->getQuery()
        ->execute();

    $this->assertTrue($r['updatedExisting'] === true);

    $doc = $this->createBuilder()
          ->hydrate()
          ->field('_id')->eq($doc->id())
          ->getQuery()
          ->getSingleResult();

    $this->assertTrue($doc->tokens()[0] == 'Idiozincrecia');
  }

    public function testUpdateMultipleFields()
    {
        $doc = new MockEntity();

        $doc->name('Gui');
        $this->unitWork->persist($doc);
        $this->unitWork->commit();

        $builder = $this->createBuilder();
        $r = $builder
            ->update()
            ->field('_id')->eq($doc->id())
            ->field('address')->set('abraveses')
            ->field('name')->set('Idiozincrecia')
            ->getQuery()
            ->execute();
        $this->assertTrue($r['updatedExisting'] === true);

        $doc = $this->createBuilder()
          ->hydrate()
          ->field('_id')->eq($doc->id())
          ->getQuery()
          ->getSingleResult();

          $this->assertTrue($doc->address() === 'abraveses');
        $this->assertTrue($doc->name() === 'Idiozincrecia');

    }

    public function testQueryGreatherThanOrEqual()
    {

    }

    protected function createBuilder()
    {
      return new \Pi\Odm\Query\QueryBuilder($this->unitWork, $this->mongoManager, get_class(new MockEntity()));
    }
}
