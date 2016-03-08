<?hh

namespace Pi\ServiceInterface\Data;
use Pi\Interfaces\ICacheProvider;
use Pi\ServiceModel\Types\Application;
use Pi\Odm\MongoRepository;
use Pi\Redis\Interfaces\IRedisClientsManager;

class ApplicationRepository extends MongoRepository {

		public ICacheProvider $cacheProvider;

		public IRedisClientsManager $redis;

		public function insert(Application $entity)
		{
			parent::insert($entity);
			
		}

		public function getRedisByDomain(string $hostname)
		{
			return $this->redis->get('app::' . $hostname);
		}

		public function setRedisDomain($appId, string $hostname)
		{
			$this->redis->set('app::' . $hostname, $appId);
		}


		protected function appRedisKey($appId)
		{
			return 'app::' . $appId;
		}
}
