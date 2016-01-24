<?hh

namespace MultiTenant;
use Pi\MockHost;
use Pi\HostConfig;
use Pi\Interfaces\IContainer;
use Pi\Odm\OdmPlugin;
use Pi\Odm\OdmConfiguration;

abstract class MultiTenantMockHost extends MockHost {

	public function __construct(?HostConfig $config = null)
	{
		parent::__construct($config);
		$this->addPreInitRequestFilterclass(new TenantResolverFilter());
		// set multitenant mode for mongodb
		$this->tryResolve('OdmConfiguration')->setMultiTenantMode(true);

	}
}
