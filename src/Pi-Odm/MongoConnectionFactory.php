<?hh
namespace Pi\Odm;

use Pi\Odm\Interfaces\IDbConnectionFactory;
use Pi\Odm\Interfaces\IDbConnection;
use Pi\Odm\MongoConnection;
use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;

class MongoConnectionFactory
  implements IContainable, IDbConnectionFactory {

    protected $ioc;

    const EXCEPTION_MONGO_CONNECT = 'mongo-connect';
    public function ioc(IContainer $container)
    {
        $this->ioc = $container;
    }

    public function create() : IDbConnection
    {
      return $this->ioc->get('IDbConnectionFactory');
    }

    public function open() : IDbConnection
    {
      try {
        
        $config = $this->ioc->get('OdmConfiguration');
        $hostname = is_string($config->getHostname()) ? $config->getHostname() : 'localhost';
        $port = is_int($config->getPort()) ? $config->getPort() : 27017;
        $c = new \MongoClient('mongodb://' . $hostname . ':' . $port);
        $con = new MongoConnection(
          $c,
          $this->ioc->get('EventManager'),
          $this->ioc->get('IEntityMetaDataFactory')
        );

        $con->ioc($this->ioc);
        return $con;  
      }
      catch(\MongoException $ex) {
        throw new \Exception(self::EXCEPTION_MONGO_CONNECT);
      } catch(\Exception $ex) {
        throw $ex;
      }
      
    }
  }
