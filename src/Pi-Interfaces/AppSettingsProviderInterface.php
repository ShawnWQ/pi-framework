<?hh

namespace Pi\Interfaces;


interface AppSettingsProviderInterface {
	
	public function getAll() : Map<string,string>;

	public function getAllKeys() : Set<string>;

	public function exists(string $key) : bool;

	public function getString(string $name) : string;

	public function getList(string $key) : Set<string>;

	public function getMap(string $key) : Map<string,string>;

	public function setString(string $name, string $value) : void;
}