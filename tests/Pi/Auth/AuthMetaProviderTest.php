<?hh

use Mocks\BibleHost;
use Pi\Auth\AuthMetaProvider;


class AuthMetaProviderTest extends \PHPUnit_Framework_TestCase {

  public function testCanSetAndGetNoProfileImgUrl()
  {
    $meta = new AuthMetaProvider();
    $meta->setNoProfileImgUrl('test-img');
    $this->assertEquals($meta->getNoProfileImgUrl(), 'test-img');
  }
}
