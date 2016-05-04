<?hh

namespace SpotEvents\ServiceModel;

use SpotEvents\ServiceModel\DTO\EventDto;
use Pi\Response;

class CreateEventResponse extends Response{
	protected $event;

	public function event(?EventDto $dto = null)
	{
		if($dto === null) return $this->dto;
		$this->dto = $dto;
	}

	/**
	 * @var EventDto
	 */
	protected $dto;
}