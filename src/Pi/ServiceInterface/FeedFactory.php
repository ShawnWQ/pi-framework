<?hh

namespace Pi\ServiceInterface;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;


class FeedFactory {

  protected $feeds = array();
  protected $instances = array();

  public function __construct(protected IContainer $ioc)
  {

  }

  public function get(string $namespace, \MongoId $entityId)
  {
    if(isset($this->instances[$namespace])) {
      return $this->instances[$namespace];
    }

    $req = $this->ioc->get('IRequest');
    $res = $this->ioc->get('IResponse');

    return $this->feeds[$namespace]($req, $res, $entityId);

  }

  public function register((function(IRequest, IResponse, \MongoId) : void) $callable, string $namespace)
  {
    $this->feeds[$namespace] = $callable;
  }
}
