<?hh
use Pi\Client\PiJsonClient;
use SpotEvents\ServiceModel\FindEvent;
class PiJsonClientTest
  extends \PHPUnit_Framework_TestCase {


    public function testCreateClient()
    {
      $client = new PiJsonClient('pi.codigo.ovh');
      $req = new FindEvent();
      $response = $client->get($req);
    }
  }
