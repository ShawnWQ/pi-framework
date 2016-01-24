<?hh

namespace MultiTenant;

use Pi\Filters\PreInitRequestFilter;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IHttpRequest;
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

		$appId = isset($_SERVER['Pi-ApplicationId']) ?
			new \MongoId($_SERVER['Pi-ApplicationId']) :
			$app->getRedisByDomain($req->serverName());

		
		$req->setAppId($appId);
	}
}
