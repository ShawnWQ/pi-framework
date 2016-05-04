<?hh

use Pi\Host\ServiceMeta;
use Mocks\VerseCreateRequest;
use Pi\ApplyTo;

class ServiceMetaTest extends \PHPUnit_Framework_TestCase {

  public function testCreate(){
    $meta = new ServiceMeta(get_class(new VerseCreateRequest()), 'post', ApplyTo::Post);
  }
}
