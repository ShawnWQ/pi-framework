<?hh
use Pi\Odm\OdmConfiguration;
use Pi\Odm\Mapping\Driver\AttributeDriver;

class OdmConfigurationTest extends \PHPUnit_Framework_TestCase {


  public function testCanConstruct()
  {
    $configuration = new OdmConfiguration();
    $configuration->setMetadataDriverImplementation(AttributeDriver::create(array()));
    $configuration->setHydratorDir(__DIR__);
    $configuration->setHydratorNamespace('MongoHydrator');
    $configuration->setAutoGenerateHydratorClasses(true);
    $configuration->setDefaultDb('test-db');
  }
}
