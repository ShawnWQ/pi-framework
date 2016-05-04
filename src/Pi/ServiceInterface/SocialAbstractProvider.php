<?hh

namespace Pi\ServiceInterface;

class SocialProviderAbstract {
	
	abstract public function publish(string $message);

	abstract public function like($id);
}

class SocialFacebookProvider extends SocialProviderAbstract {
	
	public 	function publish($message)
	{

	}

	public function like($id)
	{
		
	}
}