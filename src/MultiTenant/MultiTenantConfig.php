<?hh

namespace MultiTenant;

class MultiTenantConfig {
	
	protected string $mainHost;

	protected array $slavesHost;

	protected string $mainDb;

	protected array $slavesDb;
}