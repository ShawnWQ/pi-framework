<?hh

use Mocks\SpotEventMockHost,
	Mocks\AuthMock,
	Mocks\MockHostProvider,
	SpotEvents\ServiceModel\OpenWeatherRequest,
	SpotEvents\ServiceModel\OpenWeatherResponse,
	SpotEvents\ServiceInterface\OpenWeaterMapService;

class OpenWeatherMapServiceTest extends \PHPUnit_Framework_TestCase {

	protected $host;

	protected OpenWeaterMapService $weaterSvc;

	protected string $mockProviderResponse;

	public function setUp()
	{	
		$this->mockProviderResponse = file_get_contents(getcwd() . '/tests/SpotEvents/open-weater-response.json');
		$this->host = new SpotEventMockHost();
		$this->host->init();
	}

	public function dontrunToDontAbuaseFreeApi()
	{
		$req = new OpenWeatherRequest();
		$res = MockHostProvider::execute($req);
		$this->assertTrue($res instanceof OpenWeatherResponse);
	}

	public function testCanConvertResponseToDto()
	{
		$this->weaterSvc = $this->host->container()->get('SpotEvents\ServiceInterface\OpenWeatherMapService');
		$obj = $this->weaterSvc->convertResponseToDto($this->mockProviderResponse);
		$this->assertTrue($obj instanceof OpenWeatherResponse);
	}
	
}