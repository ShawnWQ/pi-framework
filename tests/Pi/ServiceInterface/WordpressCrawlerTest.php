<?hh

use Pi\Auth\UserEntity,
	Pi\Validation\ValidationException,
	Pi\ServiceInterface\WordpressCrawler,
	Pi\ServiceModel\ApplicationChangeDomain,
	Pi\ServiceModel\ApplicationChangeDomainResponse,
	Pi\ServiceModel\ApplicationCreateRequest,
	Pi\ServiceModel\ApplicationCreateResponse,
	Pi\ServiceModel\ApplicationDto,
	Pi\ServiceModel\ApplicationGetRequest,
	Pi\ServiceModel\ApplicationGetResponse,
	Pi\ServiceModel\ApplicationFindRequest,
	Pi\ServiceModel\ApplicationFindResponse,
	Pi\ServiceModel\UpdateApplicationMailRequest,
	Pi\ServiceModel\UpdateApplicationMailResponse,
	Pi\ServiceModel\GetApplicationMailRequest,
	Pi\ServiceModel\GetApplicationMailResponse,
	Pi\ServiceModel\Types\Application,
	Pi\Common\RandomString,
	Mocks\MockHostProvider,
	Mocks\OdmContainer;

class WordpressCrawlerTest extends \PHPUnit_Framework_TestCase{

	public function setUp()
	{
		
	}

	public function testCanFetchDefaultWordpressInstallationPosts()
	{
		$crawler = $this->getWpCrawler();
		$crawler->fetch('http://www.jornaldocentro.pt/');
	}

	public function testCanFectchAndSetCacheUpdated()
	{
		$before = new \DateTime('now');
		$uri = 'http://www.jornaldocentro.pt/';
		$crawler = $this->getWpCrawler();
		$crawler->fetch($uri);
		$cache = OdmContainer::get()->get('ICacheProvider');
		
		$cached = $cache->get(WordpressCrawler::redisDomainModified($uri));
		$cachedDate = new \DateTime($cached);
		$this->assertTrue($before->getTimestamp() < $cachedDate->getTimestamp());
	}

	public function testCanExtractIdFromGuid()
	{
		$guid = 'http://www.jornaldocentro.pt/?p=9293';
		$id = WordpressCrawler::extractIdFromGuid($guid);
		$this->assertEquals($id, '9293');
	}

	public function testCanExtractImageFromBody()
	{
		$body = '<p><img src="http://google.com/i.png" /></p><p><img src="http://google.com/j.png" /></p>';
		$image = WordpressCrawler::extractImageFromBody($body);
		$this->assertEquals($image, "http://google.com/i.png");
	}

	protected function getWpCrawler()
	{
		return OdmContainer::get()->get('Pi\ServiceInterface\WordpressCrawler');
	}
}