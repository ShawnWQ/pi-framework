<?hh

namespace SpotEvents\ServiceModel;
use SpotEvents\ServiceModel\DTO\EventDto;
use Pi\Response;

class CreateTicketResponse extends Response {

	protected EventDto $ticket;

	public function getTicket() 
	{
		return $this->ticket;
	}

	public function setTicket(TicketDto $dto)
	{
		$this->ticket = $dto;
	}
}