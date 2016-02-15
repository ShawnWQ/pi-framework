<?hh

namespace Pi;

use Pi\Interfaces\AppSettingsProviderInterface,
	Pi\Interfaces\AppSettingsInterface,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer;

class AppSettings implements IContainable, AppSettingsInterface {

	public function __construct(protected AppSettingsProviderInterface $provider)
	{

	}

	public function ioc(IContainer $ioc)
	{

	}
	
	public function getAll() : Map<string,string>
	{
		return $this->provider->getAll();
	}

	public function getAllKeys() : Set<string>
	{
		return $this->provider->getAllKeys();
	}

	public function exists(string $key) : bool
	{
		return $this->provider->exists($key);
	}

	public function getString(string $name) : string
	{
		return $this->provider->getString($name);
	}

	public function getList(string $key) : Set<string>
	{
		return $this->provider->getList($key);
	}

	public function getMap(string $key) : Map<string,string>
	{
		return $this->provider->getMap($key);
	}

	public function setString(string $name, string $value) : void
	{
		return $this->provider->setString($name, $value);
	}
}