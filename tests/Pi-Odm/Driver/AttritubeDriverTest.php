<?hh
use Pi\Odm\Mapping\EntityMetaDataFactory;
use Pi\Odm\Mapping\EntityFieldMapping;
use Pi\Odm\Mapping\EntityMetaData;
use Pi\Odm\Mapping\Driver\AttributeDriver;
use Pi\Odm\MongoEntity;
use Mocks\ADT1;

<<Document,Collection("News"),MultiTenant>>
class NewsEntity {
  <<Id>>
  public function id($id = null)
  {
    if(is_null($id)) return $this->id;
    $this->id = $id;
  }
  <<String,MaxLength('500')>>
  public function name($value = null)
  {
    if(is_null($value)) return $value;
    $this->name = $value;
  }

  protected $name;

  protected $id;
}

<<SubDocument('NewsEntity')>>
class NewsInfoEntity {
  <<Id>>
  public function id($id = null)
  {
    if(is_null($id)) return $this->id;
    $this->id = $id;
  }
  <<String,MaxLength('500')>>
  public function name($value = null)
  {
    if(is_null($value)) return $value;
    $this->name = $value;
  }

  protected $name;

  protected $id;
}

<<Document,Collection("User")>>
class TestEntity {

  <<ReferenceMany("NewsEntity"),Cascade('All')>>
  public function newsInfo($value = null)
  {
    if(is_null($value)) return $value;
    $this->newsInfo = $value;
  }

  <<String,MaxLength('500')>>
  public function name($value = null)
  {
    if(is_null($value)) return $value;
    $this->name = $value;
  }

  <<String,Email,Encrypt>>
  public function email($value = null)
  {
    if(is_null($value)) return $value;
    $this->email = $value;
  }

  <<Id>>
  public function id($id = null)
  {
    if(is_null($id)) return $this->id;
    $this->id = $id;
  }

  protected $id;

  protected $name;

  protected $email;

  protected $newsInfo;
}
class AttributeDriverTest extends \PHPUnit_Framework_TestCase{

  public function testLoadMetadataForClass()
  {
    $class = new TestEntity();
    $metaData = new EntityMetaData(get_class($class));
    $driver = $this->createDriver();
    $this->assertFalse(!is_null($metaData->getId()));

    // $class set repository
    // $class set Collection
    //

    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertTrue(!is_null($metaData->getId()));
  }

  public function testSetInheriranceType()
  {
    $class = new ADT1();
    $metaData = new EntityMetadata(get_class($class));
    $driver = $this->createDriver();
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertEquals('Single', $metaData->getInheritanceType());
    $this->assertEquals('type', $metaData->getDiscriminatorField());
  }

  public function testSetMultiTenantMode()
  {
    $class = new NewsEntity();
    $metaData = new EntityMetaData(get_class($class));
    $driver = $this->createDriver();
    $this->assertFalse($metaData->getMultiTenantMode());
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertTrue($metaData->getMultiTenantMode());
  }

  private function createDriver()
  {
    return new AttributeDriver(array());
  }
}
