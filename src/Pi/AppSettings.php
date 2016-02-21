<?hh

namespace Pi;

use Pi\Interfaces\AppSettingsProviderInterface,
	Pi\Interfaces\AppSettingsInterface,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\IHostConfig;

class AppSettings implements IContainable, AppSettingsInterface {

	protected Map<string,string> $defaults;

	public function __construct(protected AppSettingsProviderInterface $provider, IHostConfig $appConfig)
	{
		$this->defaults = Map{};
		$this->defaults
			->add(Pair{'oauth.redirectUrl', ''})
			->add(Pair{'oauth.callback', ''})
			->add(Pair{'oauth.requestTokenUrl', ''})
			->add(Pair{'oauth.authorizeUrl', ''})
			->add(Pair{'oauth.accessTokenUrl', ''})
			->add(Pair{'oauth.saveExtendedUserInfo', ''})
			->add(Pair{'oauth.facebook.redirectUrl', $appConfig->getOauthFacebookRedirectUrl() ?: '' })
			->add(Pair{'oauth.facebook.callback', $appConfig->getOauthFacebookCallbackUrl() ?: ''})
			->add(Pair{'oauth.facebook.requestTokenUrl', $appConfig->getOauthFacebookTokenUrl() ?: ''})
			->add(Pair{'oauth.facebook.authorizeUrl', $appConfig->getOauthFacebookAuthorizeUrl() ?: ''})
			->add(Pair{'oauth.facebook.accessTokenUrl', $appConfig->getOauthFacebookAccessTokenUrl() ?: ''})
			->add(Pair{'oauth.facebook.appId', $appConfig->getOauthFacebookAppId() ?: ''})
			->add(Pair{'oauth.facebook.appSecret', $appConfig->getOauthFacebookAppSecret() ?: ''})
			->add(Pair{'oauth.facebook.permissions', $appConfig->getOauthFacebookPermissions() ?: ''})
			->add(Pair{'oauth.facebook.fields', $appConfig->getOauthFacebookFields() ?: ''});

		//			->add(Pair{'oauth.facebook.saveExtendedUserInfo', ''})
	}

	public function ioc(IContainer $ioc) { }
	
	public function getAll() : Map<string,string>
	{
		return $this->provider->getAll();
	}

	public function getAllKeys() : Set<string>
	{
		return $this->provider->getAllKeys();
	}

	public function exists(string $key) : bool
	{
		return $this->provider->exists($key);
	}

	public function getString(string $name) : string
	{
		return $this->provider->getString($name);
	}

	public function getList(string $key) : Set<string>
	{
		return $this->provider->getList($key);
	}

	public function getMap(string $key) : Map<string,string>
	{
		return $this->provider->getMap($key);
	}

	public function setString(string $name, string $value) : void
	{
		return $this->provider->setString($name, $value);
	}
}