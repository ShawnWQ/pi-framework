<?hh

namespace Pi\Odm;
use Mocks\EntityRepository;
use Mocks\OdmContainer;
use Mocks\MockEntity;
use Mocks\MockEntity2;
use Mocks\MockEntity3;
use Mocks\BibleHost;
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\Repository\RepositoryFactory;
use Pi\Odm\Query\UpdateQueryBuilder;

class MongoRepositoryTest extends \PHPUnit_Framework_TestCase {

	protected $repositoryFactory;

	protected $container;

    protected $unitWork;

	public function setUp()
	{
		$this->container = OdmContainer::get();
        $this->unitWork = $this->container->get('UnitWork');
		$this->repositoryFactory = new RepositoryFactory($this->container->get('MongoManager'), $this->container->get('EventManager'));
	}

	public function testCanInsertEntityWithIdString()
	{
		$entity = new MockEntity3();

		//die(print_r($entity));
	}

	public function testCanCountCollection()
	{
		$repository = $this->container->get('Mocks\EntityRepository');
		$count = $repository->count();
	}

	public function testCanGetRepositoryByEntity()
	{
		$repository = $this->container->get('Mocks\EntityRepository');
		$this->assertNotNull($repository);
	}

  public function testGetQueryBuilder()
  {
      $repository = $this->container->get(get_class(new EntityRepository()));
      $builder = $repository->queryBuilder();
      $this->assertTrue($builder instanceof QueryBuilder);
  }

	public function testInsert()
	{
		$repository = $this->getRepository();
		$entitya = $this->createEntity();
		echo spl_object_hash($entitya) . '###';
		$this->assertTrue(\MongoId::isValid((string)$entitya->id()) == true);


		$entityb = $this->createEntity();
		$this->assertTrue(\MongoId::isValid((string)$entityb->id()) == true);
		echo spl_object_hash($entityb). '###';

		$entityc = $this->createEntity();
		echo spl_object_hash($entityc). '###';
		$this->assertTrue(\MongoId::isValid((string)$entityc->id()) == true);

		$entityd = $this->createEntity();
		echo spl_object_hash($entityd). '###';
		$this->assertTrue(\MongoId::isValid((string)$entityd->id()) == true);

	}

	protected function insertEntity()
	{
		$entity = new MockEntity();
		$entity->address('asd');
		$this->getRepository()->insert($entity);
		return $entity;
	}

	public function testPushInscriptions()
	{
		$repository = $this->getRepository();
		$entity = $this->insertEntity();
		$emb = new MockEntity2();
		$emb->name('Gui');

		$repository->pushInscriptions($entity->id(), $emb);


		$r = $repository->queryBuilder()
			->hydrate()
			->field('_id')->eq($entity->id())
			->getQuery()
			->getSingleResult();

		$this->assertTrue($r->inscriptions()[0]->name() === "Gui");

	}

	public function testPushTokens()
	{
		$repository = $this->getRepository();
		$entity = $this->insertEntity();
		$repository->pushToken($entity->id(), 'asd');

		$r = $repository->queryBuilder()
			->hydrate()
			->field('_id')->eq($entity->id())
			->getQuery()
			->getSingleResult();
		$this->assertTrue($r->tokens()[0] === "asd");
	}
	public function testUpdate()
	{
		$repository = $this->getRepository();

		$entity = $this->createEntity();

		$repository->queryBuilder()
            ->update()
            ->field('_id')->eq($entity->id())
            ->field('address')->set('ee')
            ->getQuery()
            ->execute();
		$updated = $repository->get($entity->id());
		$this->assertTrue($updated instanceof MockEntity);
		$this->assertTrue($updated->address() === 'ee');

	}

	public function testCanFindAndUpdate()
	{
			$entity = $this->createEntity();
			$repo = $this->getRepository();

			

	}

    private function createEntity()
    {
        $entity = new MockEntity();
        $entity->name('Gui');
				$entity->counter(4);
        $this->unitWork->persist($entity);
        $this->unitWork->commit();

        return $entity;
    }

    private function getRepository()
	{
		$repo = $this->container->get('Mocks\EntityRepository');
		if($repo === null) {
			throw new \Exception('repository not registered');
		}
		return $repo;
	}
}
