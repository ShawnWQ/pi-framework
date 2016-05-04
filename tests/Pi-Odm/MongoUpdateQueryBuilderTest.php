<?hh
use Pi\Odm\MongoUpdateQueryBuilder;
class MongoUpdateQueryBuilderTest extends \PHPUnit_Framework_TestCase {


  public function setUp()
  {

  }

  public function testUpdateBuilder()
  {
    $builder = new MongoUpdateQueryBuilder();
    $builder
      ->field('id')->set('a')
      ->field('name')->set('b');

    $query = $builder->getQuery();

    $this->assertTrue(is_array($query[0]['$set']));
  }
}
