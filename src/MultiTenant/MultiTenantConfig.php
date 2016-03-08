<?hh

namespace MultiTenant;

use Pi\HostConfig;


class MultiTenantConfig extends HostConfig {
	
	protected string $mainHost;

	protected array $slavesHost;

	protected string $mainDb;

	protected array $slavesDb;

	public function __construct(
		protected ?\MongoId $appId)
	{
		parent::__construct();
	}

	public function getAppId() : ?\MongoId
	{
		return $this->appId;
	}
}