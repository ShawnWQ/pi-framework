<?hh

namespace Mocks;
use Pi\Odm\OdmConfiguration;
use Pi\Odm\Mapping\Driver\AttributeDriver;

class MockOdmConfiguration{
  public static function get()
  {
    $configuration = new OdmConfiguration();
    $configuration->setMetadataDriverImplementation(AttributeDriver::create(array()));
    $configuration->setHydratorDir(__DIR__ . DIRECTORY_SEPARATOR . 'Hydrators');
    $configuration->setHydratorNamespace('Mocks\\Hydrators');
    $configuration->setAutoGenerateHydratorClasses(true);
    $configuration->setDefaultDb('test-db');

    return $configuration;
  }
}
