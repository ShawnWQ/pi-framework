<?hh
use Pi\Host\ServiceScanner;
use Pi\PiFileMapper;
use Pi\Host\ServiceMeta;

class ServiceScannerTest
	extends \PHPUnit_Framework_TestCase {

		public function testCanGetMetaEncodeAndDecode()
		{
			$mapper = new PiFileMapper(Set{__DIR__ . '/../../Mocks/'});
			$scanner = new ServiceScanner($mapper);

			$meta = $scanner->getMeta();

			$encoded = json_encode($meta);
			$coded = json_decode($encoded);

			$cached = array();
			foreach($coded as $serviceType => $serviceMeta){

				$m = new ServiceMeta($serviceMeta->serviceType);
				foreach($serviceMeta->meta as $key => $value){

					if(property_exists($value, 'requestType') && property_exists($value, 'methodName'))
						$m->add($value->requestType, $value->methodName);
				}
				$cached[] = $m;
			}
		}
	}
