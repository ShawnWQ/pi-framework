<?hh

namespace Pi\Interfaces;

interface IHostConfig {

    public function get(string $key, ?string $default = null);

    public function webHostPhysicalPath(string $values = null);

    public function oAuths(?array $values = null) : mixed;

    public function baseUri($value = null);

    public function defaultContentType($value = null);

    public function configsPath($value = null);

    public function cacheFolder($value = null);

    public function loggerFolder($value = null);

    public function appId($value = null);

    public function getConfigsPath() : string;

    public function domain($value = null);

    public function getOauthFacebookTokenUrl() : ?string;

    public function setOauthFacebookTokenUrl(string $value) : void;

    public function getOauthFacebookRedirectUrl() : ?string;

    public function setOauthFacebookRedirectUrl(string $value) : void;

    public function getOauthFacebookCallbackUrl() : ?string;

    public function setOauthFacebookCallbackUrl(string $value) : void;

    public function getOauthFacebookConsumerKey() : ?string;

    public function setOauthFacebookConsumerKey(string $value) : void;

    public function getOauthFacebookConsumerSecret() : ?string;

    public function setOauthFacebookConsumerSecret(string $value) : void;

    public function getOauthFacebookAppId() : ?string;

    public function setOauthFacebookAppId(string $value) : void;

    public function getOauthFacebookAppSecret() : ?string;

    public function setOauthFacebookAppSecret(string $value) : void;

    public function getOauthFacebookPermissions() : ?string;

    public function setOauthFacebookPermissions(string $value) : void;

    public function getOauthFacebookFields() : ?string;

    public function setOauthFacebookFields(string $value) : void;

    public function getOauthFacebookAuthorizeUrl() : ?string;

    public function setOauthFacebookAuthorizeUrl(string $value) : void;

    public function getOauthFacebookAccessTokenUrl() : ?string;

    public function setOauthFacebookAccessTokenUrl(string $value) : void;
}
