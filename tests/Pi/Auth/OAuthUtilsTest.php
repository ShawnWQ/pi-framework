<?hh

use Mocks\BibleHost;
use Pi\Auth\OAuthUtils;


class OAuthUtilsTest extends \PHPUnit_Framework_TestCase {

  public function testCanEncodeUrlWithRfc3986() {
  	$url = 'asdasdasd';
    $value = OAuthUtils::urlencodeRfc3986($url);
  }

  public function testCanEncodeArrayOfUrlsWithRfc3986() {

  }

  public function testCanNormalizeHttpMethod() {
  	$val = OAuthUtils::getNormalizedHttpMethod('post');
  	$this->assertEquals($val, 'POST');
  }

  public function testCanNormalizedHttpUrl() {
  	$val = OAuthUtils::getNormalizedHttpUrl('codigo.ovh');
  	$this->assertEquals($val, 'https://codigo.ovh:463');
  }
}
