<?hh

namespace MultiTenant;

use Pi\Filters\PreInitRequestFilter;
use Pi\Interfaces\IRequest,
	Pi\Interfaces\IHttpRequest;
use Pi\Interfaces\IResponse;
use Pi\ServiceInterface\Data\ApplicationRepository;
use Pi\Host\HostProvider;
use Pi\Extensions;

class TenantResolverFilter extends PreInitRequestFilter {

	public ?ApplicationRepository $appRepo;

	public function nullthrows<TType>(TType $x, ?string $message = null) : TType {
		if (is_null($x)) {
			throw new \Exception($message ?: 'Unexpected null');
		}
		return $x;
	}
	public function execute(IRequest $req, IResponse $res, mixed $requestDto) : void
	{
		$app = $this->appRepo = HostProvider::instance()->tryResolve('Pi\ServiceInterface\Data\ApplicationRepository');
		$this->nullthrows($app, 'ApplicationRepository isnt injected in TenantResolveFilter');

		if(!$req instanceof IHttpRequest) {
			throw new \Exception('MultiTenant not available for CLI');
		}
		$config = HostProvider::instance()->config();
		if($req->headers()->contains('X-Pi-Application')) {
			$req->setAppId(new \MongoId($req->headers()['X-Pi-Application']));
		} else if(!is_null($config->getAppId())) {
			$req->setAppId($config->getAppId());	
		}

	}
}
