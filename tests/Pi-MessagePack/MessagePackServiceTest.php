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
    $this->service = new MessagePackService();
  }

  public function testServiceCanSerializeArray()
  {
    $a = array(1, 2, 3);
    $res = $this->service->serialize($a);

    $des = $this->service->unserialize($res);
    $this->assertTrue(is_array($des));
  }

  public function testCanSerializeClass()
  {
    $dto = new RandomClass();
    $res = $this->service->serialize($dto);
    $des = $this->service->unserialize($res);
    $this->assertTrue($res instanceof RandomClass);
    die(print_r($res));
  }
}
