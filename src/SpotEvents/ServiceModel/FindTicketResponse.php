<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;

class FindTicketResponse extends Response {

	protected $tickets;

	public function getTickets()
	{
		return $this->tickets;
	}

	public function setTickets($tickets)
	{
		$this->tickets = $tickets;
	}
}