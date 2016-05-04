<?hh

namespace Pi;



class ErrorMessages {

	static string $notAuthenticated = 'Not authorized';
	
	static function unknownAuthProviderFmt(string $provider) : string
	{
		return 'the provider $provider is not supported.';
	}
}