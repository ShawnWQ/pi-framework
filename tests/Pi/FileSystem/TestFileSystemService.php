<?hh

use Mocks\BibleHost,
    Mocks\MockHostProvider,
    Pi\FileSystem\FileSystemService,
    Pi\FileSystem\FileFind,
    Pi\FileSystem\FileEntity;




class TestFileSystem extends \PHPUnit_Framework_TestCase {

  protected $host;

  protected $repository;

  public function __construct()
  {
    $this->host = new BibleHost();
    $this->host->init();
    $this->repository = $this->host->container()->get('Pi\FileSystem\FileEntityRepository');
  }

  public function testCanFindAllPublicFiles()
  {
    $count = $this->repository->count();
    $file = new FileEntity();
    $this->repository->insert($file);
    $req = new FileFind();
    $res = MockHostProvider::execute($req);
    $this->assertTrue(count($res->getFiles()) <=  $count + 1);
  }

  public function testCanFindAllPublicFilesPaginated()
  {
    for($i = 0; $i < 4; $i++) {
      $file = new FileEntity();
      $this->repository->insert($file);
    }
    $req = new FileFind();
    $req->setLimit(1);
    $res = MockHostProvider::execute($req);
    //die(print_r($res));
    $this->assertTrue(count($res->getFiles()) ==  1);

  }
}
