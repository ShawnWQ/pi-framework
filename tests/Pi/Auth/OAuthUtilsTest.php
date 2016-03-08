<?hh

use Mocks\BibleHost;
use Pi\Auth\OAuthUtils;


class OAuthUtilsTest extends \PHPUnit_Framework_TestCase {

  public function testCanEncodeUrlWithRfc3986()
  {
  	$vals = array(
      'Ladies + Gentlemen' => 'Ladies%20%2B%20Gentlemen',
      'An encoded string!' => 'An%20encoded%20string%21',
      'Dogs, Cats & Mice' => 'Dogs%2C%20Cats%20%26%20Mice',
      '☃' => '%E2%98%83'
    );
    
    array_walk($vals, function($val, $key) {
      $this->assertEquals($val, OAuthUtils::urlencodeRfc3986($key));
    });    
  }

  public function testCanEncodeArrayOfUrlsWithRfc3986() 
  {

  }

  public function testCanNormalizeHttpMethod() 
  {
  	$val = OAuthUtils::getNormalizedHttpMethod('post');
  	$this->assertEquals($val, 'POST');
  }

  public function testCanNormalizedHttpUrl()
  {
  	$val = OAuthUtils::getNormalizedHttpUrl('codigo.ovh');
  	$this->assertEquals($val, 'https://codigo.ovh:463');
  }
}
