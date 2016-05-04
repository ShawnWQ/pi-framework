<?hh

namespace Test;

use Pi\Interfaces\ICacheProvider,
	Pi\Cache\InMemoryCacheProvider;




abstract class BaseTest extends \PHPUnit_Framework_TestCase{
	
	protected function buildCacheProvider(string $type = 'default') : ICacheProvider
	{
		return new InMemoryCacheProvider();
	}
}