<?hh
namespace Pi\Odm;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;
use Pi\EventManager;
class DatabaseManager implements IContainable {

	public function __construct(
		protected MongoConnection $con,
		protected EventManager $eventManager)
	{
	}
	public function selectDatabase($dbName)
  	{
    	return $this->doSelectDatabase($dbName);
  	}
	public function doSelectDatabase($dbName)
	{
		$db = $this->con->getMongoClient()->selectDB($dbName);
		return new MongoDatabase($db, $this->eventManager);
	}

	public function ioc(IContainer $container)
	{
	}
}
