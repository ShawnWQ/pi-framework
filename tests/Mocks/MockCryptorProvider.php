<?hh

namespace Pi\Auth\AbstractCryptorProvider;





class MockCryptorProvider extends AbstractCryptorProvider {

	public function __construct()
	{

	}
	
	public function encrypt(string $hash) : string
	{
		return $hash + "1";
	}
}