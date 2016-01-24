<?hh
use SpotEvents\ServiceInterface\IfThenPaymentProvider;

class IfThenPaymentProviderTest extends \PHPUnit_Framework_TestCase {
	
	protected $provider;

	public function setUp()
	{
		$this->provider = new IfThenPaymentProvider();
	}

	public function testCreateReference()
	{
		$ref = $this->provider->createRef(11873, 578, 1, 22.45);
		$this->assertEquals($ref, '578000195');

        $ref = $this->provider->createRef(11873, 578, 1, 22.40);
        $this->assertEquals($ref, '578000113');
	}
	
}