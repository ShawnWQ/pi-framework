<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/21/15
 * Time: 1:06 AM
 */

namespace SpotEvents\ServiceInterface;

use SpotEvents\ServiceModel\CreateTicketResponse;
use SpotEvents\ServiceModel\CreateTicketRequest;
use SpotEvents\ServiceModel\GetTicketRequest;
use SpotEvents\ServiceModel\GetTicketResponse;
use SpotEvents\ServiceModel\FindTicketRequest;
use SpotEvents\ServiceModel\FindTicketResponse;
use SpotEvents\ServiceModel\Types\Ticket;
use SpotEvents\ServiceModel\TicketDto;
use SpotEvents\ServiceInterface\Data\TicketRepository;
use Pi\Common\ClassUtils;
use Pi\Service;

class TicketService extends Service {

	public TicketRepository $ticketRepo;

	<<Request,Auth>>
	public function get(GetTicketRequest $request)
	{
		$response = new GetTicketResponse();
		$dto = $this->ticketRepo->getAs($request->getId(), 'SpotEvents\ServiceModel\TicketDto');
		$response->setTicket($dto);
		return $response;
	}

	<<Request,Auth>>
	public function find(FindTicketRequest $request)
	{
		$response = new FindTicketResponse();
		$res = $this->ticketRepo->queryBuilder('SpotEvents\ServiceModel\TicketDto')
			->find()
			->hydrate(true)
			->getQuery()
			->toArray();
		$response->setTickets($res);
		return $response;
	}

	<<Request,Auth>>
	public function create(CreateTicketRequest $request)
	{
		$response = new CreateTicketResponse();
		$entity = new Ticket();

		ClassUtils::mapDto($request, $entity);

		$dto = new TicketDto();
		ClassUtils::mapDto($entity, $dto);

		$response->setTicket($dto);
		return $response;
	}
}