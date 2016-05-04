<?hh

use Warez\ServiceModel\FacebookBotLogin;
use Warez\ServiceModel\FacebookBotLoginResponse;
use Mocks\WarezHost;
use Mocks\MockHostProvider;
use Pi\Common\ClassUtils;
use Pi\Host\HostProvider;
use Pi\PhpUnitUtils;
class FacebookBotMock extends \PHPUnit_Framework_TestCase {

	protected $movieRepo;

	public function testCanLogin()
	{
		$request = new FacebookBotLogin();
		$request->setEmail('email@guilhermecardoso.pt');
		$request->setPassword('dk(L)2012');
		$res = MockHostProvider::executeWarez($request);
		
	}

}