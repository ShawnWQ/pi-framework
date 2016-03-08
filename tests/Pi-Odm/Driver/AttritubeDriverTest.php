<?hh
use Pi\Odm\Mapping\EntityMetaDataFactory,
    Pi\Odm\Mapping\EntityFieldMapping,
    Pi\Odm\Mapping\EntityMetaData,
    Pi\Odm\Mapping\Driver\AttributeDriver,
    Pi\Odm\MongoEntity,
    Pi\Odm\MappingType,
    Pi\EventManager,
    Pi\Interfaces\ICacheProvider,
    Mocks\ADT1,
    Mocks\BibleHost;

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
   $metaData = $this->getMockMetadata();
    $driver = $this->createDriver();
    $this->assertFalse(!is_null($metaData->getId()));
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertTrue(!is_null($metaData->getId()));
  }

  public function testSetInheriranceType()
  {
    $metaData = $this->getMockMetadata();
    $driver = $this->createDriver();
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertEquals('Single', $metaData->getInheritanceType());
    $this->assertEquals('type', $metaData->getDiscriminatorField());
  }

  public function testCanSetStringMapping()
  {    
    $metaData = $this->getMockMetadata();
    $driver = $this->createDriver();
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertNotNull($metaData->mappings()['text']);
    $this->assertEquals($metaData->mappings()['text']->getPHPType(), MappingType::String);
  }

  public function testCanMapDateTimeField()
  {
    $metaData = $this->getMockMetadata();
    $mapping = $metaData->mappings()['date'];
    $this->assertNotNull($mapping);
    $this->assertEquals($mapping->getPHPType(), MappingType::Date);
    $this->assertTrue($mapping->isNotNull());
  }

  public function testCanNotNullField()
  {
    $metaData = $this->loadMockMetadata();
    $mapping = $metaData->mappings()['number'];
    $this->assertNotNull($mapping);
    $this->assertTrue($mapping->isNotNull());
  }

  public function testCanMapCollectionField()
  {
    $metaData = $this->loadMockMetadata();
    $this->assertNotNull($metaData->mappings()['collection']);
    $this->assertEquals($metaData->mappings()['collection']->getPHPType(), MappingType::Collection);
  }

  public function testSetMultiTenantMode()
  {
    $metaData = $this->loadMockMetadata();
    $driver = $this->createDriver();
    $this->assertFalse($metaData->getMultiTenantMode());
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    $this->assertTrue($metaData->getMultiTenantMode());
  }

  protected function createMockClass() : ADT1
  {
    $class = new ADT1();
    $class->set('random', 1, new \DateTime());
    return $class;
  }

  protected function getMockMetadata() : EntityMetadata
  {
    return new EntityMetadata(get_class($this->createMockClass()));
  }

  protected function loadMockMetadata(?EntityMetadata $metaData = null) : ?EntityMetadata
  {
    if($metaData == null) {
      $metaData = $this->getMockMetadata();
    }
    $driver = $this->createDriver();
    $driver->loadMetadataForClass($metaData->getName(), $metaData);
    return $metaData;
  }

  private function createDriver()
  {
    $this->host = new BibleHost();
    $this->host->init();
    return $this->host->resolve('IMappingDriver');
  }
}
