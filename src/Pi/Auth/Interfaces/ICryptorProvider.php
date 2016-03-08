<?hh

namespace Pi\Auth\Interfaces;

interface ICryptorProvider {
	
	function encrypt(string $hash) : string;
}