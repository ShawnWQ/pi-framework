<?hh

namespace Pi\Interfaces;

interface IServiceExecute {
	public function execute(IRequest $context, $instance, $request);
}