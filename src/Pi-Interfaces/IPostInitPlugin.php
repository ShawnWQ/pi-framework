<?hh

namespace Pi\Interfaces;

interface IPostInitPlugin {
	public function configure(IPiHost $appHost) : void;
}
