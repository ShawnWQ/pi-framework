<?hh

namespace Mocks;

use Mocks\MongoOdmConfiguration,
	Mocks\MockOdmConfiguration,
	Mocks\MockEntity,
	Mocks\EntityRepository,
	Pi\Odm\Hydrator\MongoDBHydratorFactory,
	Pi\Odm\UnitWork,
	Pi\Interfaces\IContainer,
	Pi\EventManager,
	Pi\Odm\MongoConnection,
	Pi\Odm\Mapping\Driver\AttributeDriver,
	Pi\Odm\Mapping\EntityMetaDataFactory,
	Mocks\BibleHost;




class OdmContainer {
  
  public static function get()
  {
    $host = new BibleHost();
    $host->init();
    $host->container()->registerRepository(MockEntity::class, EntityRepository::class);
    return $host->container();
  }
}
