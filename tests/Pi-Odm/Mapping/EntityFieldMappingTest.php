<?hh

class EntityFieldMappingTest extends \PHPUnit_Framework_TestCase{

    protected $mongoManager;

    protected $host;

    public function setUp()
    {
        $this->host = new \Mocks\BibleHost();
        $this->host->init();
        $this->mongoManager = $this->host->container()->get('MongoManager');
    }

    public function testIdentifyEmbedMany()
    {
        $entity = new \Mocks\MockEntity();
        $metadata = $this->mongoManager->getClassMetadata(get_class($entity));

        $this->assertTrue($metadata instanceof \Pi\Odm\Mapping\EntityMetaData);
        $mappings = $metadata->mappings();
        $this->assertTrue($mappings['inscriptions']->isEmbedMany());
    }
}
