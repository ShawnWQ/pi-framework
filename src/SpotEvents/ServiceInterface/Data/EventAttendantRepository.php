<?hh

namespace SpotEvents\ServiceInterface\Data;

use Pi\Odm\MongoRepository;
use SpotEvents\ServiceModel\Types\EventAttendant;
use Pi\Odm\BucketCollection;
use Pi\ServiceInterface\Data\BucketRepository;
use Pi\Redis\Interfaces\IRedisClientsManager;

class EventAttendantRepository<TBucketAttendant> extends BucketRepository<TBucketAttendant>{
	
	
}