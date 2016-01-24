<?hh

namespace Mocks;
use Mocks\MongoOdmConfiguration;
use Mocks\MockOdmConfiguration;
use Mocks\MockEntity;
use Mocks\EntityRepository;
use Pi\Odm\Hydrator\MongoDBHydratorFactory;
use Pi\Odm\UnitWork;
use Pi\PiContainer;
use Pi\Interfaces\IContainer;
use Pi\EventManager;
use Pi\Odm\MongoConnection;
use Pi\Odm\Mapping\Driver\AttributeDriver;
use Pi\Odm\Mapping\EntityMetaDataFactory;
use Mocks\BibleHost;

class OdmContainer {
  public static function get()
  {

    $host = new BibleHost();
    $host->init();
    $host->container()->registerRepository(new MockEntity(), new EntityRepository());
    return $host->container();
  }
}
