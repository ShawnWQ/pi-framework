<?hh

namespace Collaboration\ServiceModel\Types;

use SpotEvents\ServiceModel\Types\EventEntity;


<<Collection('meeting')>>
class Meeting extends EventEntity {

	protected array $moderators;

	protected array $members;

	<<Collection>>
	public function getMembers() : mixed
	{
		return $this->members;
	}

	public function setMembers(array $values) : void
	{
		$this->members = $values;
	}

	<<Collection>>
	public function getModerators() : mixed
	{
		return $this->moderators;
	}

	public function setModerators(array $values) : void
	{
		$this->moderators = $values;
	}
}
