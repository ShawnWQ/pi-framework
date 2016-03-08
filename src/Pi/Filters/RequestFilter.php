<?hh

namespace Pi\Filters;

use Pi\Interfaces\IHasRequestFilter,
    Pi\ApplyTo,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse,
    Pi\ServiceInterface\RequestExtensions,
    Pi\Interfaces\IContainable,
    Pi\Interfaces\IContainer,
    Pi\Host\HostProvider;




abstract class RequestFilter implements IHasRequestFilter {

    protected $applyTo;

    protected $priority;

    protected $appHost;

    public function setAppHost($appHost)
    {
      $this->appHost = $appHost;
    }

    public function __construct(?ApplyTo $applyTo = null)
    {
      if($applyTo === null) {
        $applyTo = 'GET';
      }
      $this->applyTo = $applyTo;
    }

    public abstract function execute(IRequest $req, IResponse $res, $requestDto) : void;

    public function applyTo()
    {
      return $this->applyTo;
    }

    public function priority() : int
    {
      return $this->priority;
    }

    public function setPriotiry($value)
    {
      $this->priority = $value;
    }

    public function requestFilter(IRequest $req, IResponse $res, $responseDto) : void
    {
      $applyTo = RequestExtensions::httpMethodAsApplyTo($req);
      if(!is_null($applyTo))
      {
          $this->execute($req, $res, $responseDto);
      }
    }

    public function copy() : IHasRequestFilter
    {
      // clone this object and return him
      throw new Pi\NotImplementedExecption();
    }
  }
