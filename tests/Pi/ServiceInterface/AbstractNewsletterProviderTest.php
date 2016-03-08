<?hh

use Mocks\MockHostProvider;
use Mocks\MockNewsletterProvider;
use Pi\ServiceInterface\Data\NewsletterRepository;
use Pi\ServiceInterface\Data\NewsletterSubscriptionRepository;
use Pi\ServiceModel\Types\Newsletter;
use Pi\ServiceModel\Types\NewsletterSubscription;
use Mocks\BibleHost;




class AbstractNewsletterProviderTest extends \PHPUnit_Framework_TestCase {

	protected $provider;

	protected $repo;

	protected $subsRepo;

	public function setUp()
	{
		$host = new BibleHost();
		$host->init();
		$ioc = MockHostProvider::instance()->container();
		$this->repo = $repo = $ioc->tryResolve('Pi\ServiceInterface\Data\NewsletterRepository');
		$this->subsRepo = $subsRepo = $ioc->tryResolve('Pi\ServiceInterface\Data\NewsletterSubscriptionRepository');
		$this->provider = new MockNewsletterProvider($repo, $subsRepo);
	}

	public function testCanCreateNewsletter()
	{
		$entity = new Newsletter();
		$entity->setName('nome da newsletter');
		$entity->setDescription('conteudo da newsletter');

		$res = $this->provider->createNewsletter($entity);
	}

	public function testCanSubscribe()
	{
		$entity = $this->createNewsletter();
		$userId = new \MongoId();

		$res = $this->provider->subscribe($entity->id(), $userId, 'name', 'name@gmail.com');
		$this->assertEquals($res->getName(), 'name');
	}

	public function testSubscriptionExists()
	{
		$entity = $this->createNewsletter();
		$sub = new NewsletterSubscription();
		$sub->setId(new \MongoId());
		$this->subsRepo->add($entity->id(), $sub);

		$this->assertTrue($this->provider->subscriptionExists($entity->id(), $sub->getId()));
	}

	protected function createNewsletter(string $name = 'Newsletter Name', string $description = 'Newsletter Description')
	{
		$entity = new Newsletter();
		$entity->setName($name);
		$entity->setDescription($description);
		$this->repo->insert($entity);
		return $entity;
	}
}