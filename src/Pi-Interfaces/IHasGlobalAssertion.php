<?hh

namespace Pi\Interfaces;

/**
 * This method will be invoked during the build process
 * Its consided a requirement for the Application to run and should throw exceptions
 */
interface IHasGlobalAssertion {
	
	public function assertGlobalEnvironment();
}