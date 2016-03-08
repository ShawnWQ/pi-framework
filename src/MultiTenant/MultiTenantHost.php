<?hh

namespace MultiTenant;
use Pi\AppHost;
use Pi\HostConfig;
use Pi\Interfaces\IContainer;
use Pi\Odm\OdmPlugin;
use Pi\Odm\OdmConfiguration;

abstract class MultiTenantHost extends AppHost {

	public function __construct(?MultiTenantConfig $config = null)
	{
		if(is_null($config)) {
			$config = new MultiTenantConfig(new \MongoId());	
		}
		parent::__construct($config);
		$this->addPreInitRequestFilterclass(new TenantResolverFilter());
		// set multitenant mode for mongodb
		$this->tryResolve('OdmConfiguration')->setMultiTenantMode(true);
	}
}
