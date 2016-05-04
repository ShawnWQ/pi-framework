<?hh

namespace Test\Memcached;

use Pi\Memcached\MemcachedFactory,
	Pi\Memcached\MemcachedConfiguration;


class MemcachedConfigurationTest extends \PHPUnit_Framework_TestCase {
	
	public function testCanCreateConfigurationFromArray()
	{
		$config = MemcachedConfiguration::fromArray(array('instances' => array('localhost' => 25)));
		$this->assertEquals($config->instances()->get('localhost')[0], 'localhost');
	}
}