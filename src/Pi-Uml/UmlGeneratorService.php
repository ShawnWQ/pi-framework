<?hh

namespace Pi\Uml;

use Pi\Service;
use Pi\Uml\ServiceModel\UmlGenerateRequest;
use Pi\Uml\ServiceModel\UmlGenerateResponse;
use Pi\Uml\ServiceModel\UmlClass;
use Pi\Uml\ServiceModel\UmlMethod;
use Pi\Odm\DocumentManager;
use Pi\PiFileMapper;
use Pi\Common\StringUtils;
use Pi\Common\ClassUtils;

class UmlGeneratorService extends Service {

	public DocumentManager $dm;

	public function __construct()
	{
		parent::__construct();
	}

	<<Request,Route('/uml'),Method('GET')>>
	public function generate(UmlGenerateRequest $request)
	{

		$fileMapper = new PiFileMapper(Set {__DIR__ .'/.../../../'});

		$response = new UmlGenerateResponse();

		$allMap = $fileMapper->getMap();
		$map = Map{};
		$searchword = '\SpotEvents';
		$map = $allMap->filterWithKey(function($var, $value) use ($searchword) { 
			return StringUtils::startsWith($var, $searchword);  
		});

		$set = Map{};

			foreach($map as $classType => $fileName) {
					try {
						$interfaces = class_implements($classType);
						if(!$interfaces) continue;	
					}
					catch(\Exception $ex) {
						
					}
					

					$rc = new \ReflectionClass($classType);

					$meta = array();
					$methods = array();

					foreach($rc->getMethods() as $method) {

							$methods[] = new UmlMethod($method->name, 'mixed', $method->getParameters ());
					}

					$class = new UmlClass($classType, $methods);

					$set[$classType] = $class;
				
			}
		
		$response->raw($set);

		return $response;
	}
}
