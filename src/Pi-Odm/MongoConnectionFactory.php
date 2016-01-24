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
        $c = new \MongoClient();
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
