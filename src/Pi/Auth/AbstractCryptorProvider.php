<?hh

namespace Pi\Auth;

use Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer,
	Pi\Auth\Interfaces\ICryptorProvider;




/**
 * An simple service to encrypt hashes
 */
abstract class AbstractCryptorProvider implements ICryptorProvider, IContainable {
	
	abstract function encrypt(string $hash) : string;

	public function ioc(IContainer $ioc)
	{
		
	}
}