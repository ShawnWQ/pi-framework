<?hh

use Mocks\OdmContainer;
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 1:08 AM
 */
class MockLikesProvider extends \Pi\ServiceInterface\LikesProvider {
    public function __construct()
    {
        parent::__construct('mocked');
    }
}
class LikesProviderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Pi\ServiceInterface\LikesProvider
     */
    protected $provider;

    protected $container;

    protected $id1;

    protected $id2;

    public function setUp()
    {
        $this->provider = new MockLikesProvider();
        $this->container = OdmContainer::get();
        $this->container->autoWireService($this->provider);
        $this->id1 = new \MongoId();
        $this->id2 = new \MongoId();
    }

    public function testAddReturnFalseWhenAlreadyLiked()
    {
        $res = $this->provider->add($this->id1, $this->id2);
        $this->assertTrue($res);
        $res = $this->provider->add($this->id1, $this->id2);
        $this->assertFalse($res);
    }

    public function testAddLike()
    {
        $entityId = new \MongoId();
        $userId = new \MongoId();
        $this->assertFalse($this->provider->hasLiked($entityId, $userId));
        $count = $this->provider->count($entityId);
        $this->provider->add($entityId, $userId);
        $count2 = $this->provider->count($entityId);
        $this->assertTrue($count < $count2);
        $this->assertTrue($this->provider->hasLiked($entityId, $userId));
    }
}