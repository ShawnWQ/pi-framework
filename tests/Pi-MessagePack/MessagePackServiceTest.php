<?hh
use Mocks\OdmContainer;
use Pi\MessagePack\MessagePackService;
use Mocks\MockEntity;

class RandomClass {

	protected $value = 'aaasdasd';

	protected $other = 'asdasdasd';
}

class MessagePackServiceTest extends \PHPUnit_Framework_TestCase {

  protected $container;

  /**
   * @var MessagePackService
   */
  protected $service;

  public function setUp()
  {
    $this->container = OdmContainer::get();
    $this->service = $this->container->get('IServiceSerializer');
  }

  public function testServiceCanSerializeArray()
  {
    $a = array(1, 2, 3);
    $res = $this->service->serialize($a);

    $des = $this->service->deserialize($res);
    $this->assertTrue(is_array($des));
  }
}
