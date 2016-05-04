<?hh

namespace Pi\Auth;





class Md5CryptorProvider extends AbstractCryptorProvider {

	public function __construct()
	{

	}
	
	public function encrypt(string $hash) : string
	{
		return md5($hash);
	}
}