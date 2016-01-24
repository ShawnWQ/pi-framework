<?hh
use Pi\Odm\Query\QueryBuilder;
use Pi\Odm\Query\QueryType;
use Pi\Odm\Query\QueryExecutor;
use Pi\Odm\MongoManager;
use Mocks\OdmContainer;
use Mocks\MockEntity;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{

    protected $container;

    protected $mongoManager;

    protected $unitWork;

    public function setUp()
    {
        $this->container = OdmContainer::get();
        $this->mongoManager = $this->container->get('MongoManager');
        $this->unitWork = $this->container->get('UnitWork');
    }

    public function testRandom()
    {
        $col = $this->mongoManager->getDocumentCollection(get_class(new MockEntity()));
        $a = array('name' => 'asd');
        $col->insert($a);
        $result = $col->find(array('name' => 'asd'), array());
   }

}
