<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<Entity>>
abstract class AbstractFeed implements IEntity, \JsonSerializable {

	public function jsonSerialize()
	{
		$vars = get_object_vars($this);
		return $vars;
	}
	
}