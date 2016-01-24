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
}
