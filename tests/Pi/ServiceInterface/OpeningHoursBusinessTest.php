<?hh

use Pi\AppHost;
use Pi\PiHost;
use Pi\Host\ServiceController;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IHasAppHost;
use Pi\Common\RandomString;
use Mocks\BibleHost;
use Mocks\VerseGetResponse;
use Mocks\MockEnvironment;
use Mocks\TestAppHost;
use Pi\PhpUnitUtils;
use Pi\ServiceInterface\OpeningHoursBusiness;
use Pi\Odm\MongoRepository,
	Pi\Interfaces\IOpeningHoursModel,
	Pi\ServiceModel\Types\OpeningHoursSpecification;

class OHBRepo extends MongoRepository {

}

class OHBEntity extends OpeningHoursSpecification implements IOpeningHoursModel{

}


class OpeningHoursBusinessTest extends \PHPUnit_Framework_TestCase {

	protected  $host;

    public function setUp()
    {
      $this->host = new TestAppHost(new \Pi\HostConfig());
      $this->host->init();
    }


    private function createAppHost()
    {
     
    }

    
  }
