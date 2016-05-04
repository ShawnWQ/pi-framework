<?hh

namespace Pi;

use Pi\AbstractAppSettingsProvider,
	Pi\Interfaces\AppSettingsInterface,
	Pi\Interfaces\IContainable;




class MemcachedAppSettingsProvider extends AbstractAppSettingsProvider implements AppSettingsInterface {
	
	protected $settings;

	public function __construct()
	{
		$this->settings = Map{};
	}
	public function getAll() : Map<string,string>
	{
		return $this->settings();
		throw new \Exception('Not Implemented');
	}

	public function getAllKeys() : Set<string>
	{
		throw new \Exception('Not Implemented');
	}

	public function exists(string $key) : bool
	{
		return $this->settings->contains($key);
	}

	public function getString(string $name) : string
	{
		return $this->settings->get($name);
	}

	public function getList(string $key) : Set<string>
	{
		throw new \Exception('Not Implemented');
	}

	public function getMap(string $key) : Map<string,string>
	{	
		throw new \Exception('Not Implemented');
	}

	public function setString(string $name, string $value) : void	
	{
		if($this->exists($name)) {
			$this->settings->remove($name);
		}
		$this->settings->add(Pair{$name, $value});
	}
}