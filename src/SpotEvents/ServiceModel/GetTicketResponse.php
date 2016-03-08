<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;

class GetTicketResponse extends Response {

	protected TicketDto $ticket;

	public function getTicket() : TicketDto
	{
		return $this->ticket;
	}

	public function setTicket(TicketDto $dto)
	{
		$this->ticket = $dto;
	}
}