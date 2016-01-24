<?hh

use Mocks\MockHostProvider;
use Mocks\BibleHost;
use Mocks\AuthMock;
use Pi\PiContainer;
use Pi\ServiceInterface\FeedFactory;
use Pi\ServiceModel\Types\FeedAction;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Host\BasicRequest;
use Pi\Host\BasicResponse;

class FeedFactoryTest extends \PHPUnit_Framework_TestCase {

  protected $container;

  protected FeedFactory $factory;

  public function setUp()
  {
    $host = new BibleHost();
    AuthMock::mock();
    $host->init();

    $this->container = $host->container;
    $this->container->register('IRequest', function(IContainer $ioc){
      $req = new BasicRequest();
      return $req;
    });
    $this->container->register('IResponse', function(IContainer $ioc){
      $res = new BasicResponse();
      return $res;
    });

    $this->factory = new FeedFactory($this->container);
  }

  public function testCanRegisterFeed()
  {
    $callable = function(IRequest $req, IResponse $res, \MongoId $entityId) : void
    {
      $event = $req->tryResolve('Pi\ServiceInterface\Data\PlaceRepository')->get($entityId);
      $action = new FeedAction(
        new \MongoId(),
        new \DateTime('now'),
        false,
        'basic',
        'normal',
        array('event' =>
        array('title' => 'asdasd', 'id' => (string)$entityId)),
        'like-test');

        return $action;
    };
    $this->factory->register($callable, 'test');

    $r = $this->container->get('Pi\ServiceInterface\Data\PlaceRepository');
    $p = new \Pi\ServiceModel\Types\Place();
    $p->setName('asdasdasd');
    $r->insert($p);

    $action = $this->factory->get('test', $p->id());
    $this->assertEquals($action->getScope(), 'basic');

  }
}
