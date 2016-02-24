<?hh

namespace Pi\Common\Mapping\Driver;

use Pi\EventManager,
	Pi\Interfaces\ICacheProvider;



	
class ClassMappingDriver extends AbstractMappingDriver {
	

	public static function create($paths = array(), EventManager $em, ICacheProvider $cache)
	{
		return new self($paths, $em, $cache);
	}
}