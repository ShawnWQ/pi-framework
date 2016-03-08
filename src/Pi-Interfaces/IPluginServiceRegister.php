<?hh

namespace Pi\Interfaces;

interface IPluginServiceRegister {
	
	public function registerServices() : Awaitable<void>;
}