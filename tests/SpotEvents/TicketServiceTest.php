<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:43 AM
 */
use SpotEvents\ServiceModel\CreateTicketRequest;
use SpotEvents\ServiceModel\CreateTicketResponse;
use SpotEvents\ServiceModel\GetTicketRequest;
use SpotEvents\ServiceModel\GetTicketResponse;
use SpotEvents\ServiceModel\FindTicketRequest;
use SpotEvents\ServiceModel\FindTicketResponse;
use SpotEvents\ServiceModel\Types\Ticket;
use Mocks\AuthMock;
use Mocks\MockHostProvider;

class TicketServiceTest extends PHPUnit_Framework_TestCase{

    protected $host;
    protected $ticketRepo;

    public function setUp()
    {
        $this->host = new \Mocks\SpotEventMockHost();
        $this->host->init();
        AuthMock::mock();
        $tmp = __DIR__ .'/../tmp';
        $this->host->config()->configsPath($tmp);
        $this->host->config()->cacheFolder($tmp);
        $this->ticketRepo = $this->host->tryResolve('SpotEvents\ServiceInterface\Data\TicketRepository');
    }
    
    public function testCreateTicket()
    {
        $req = new CreateTicketRequest();
        $req->setPriceCurrency(2000);
        $res = MockHostProvider::execute($req);
        $this->assertTrue($res instanceof CreateTicketResponse);
        $this->assertEquals($res->getTicket()->getPriceCurrency(), 2000);
    }

    public function testGetTicket()
    {
        $ticket = $this->createTicket();
        $req = new GetTicketRequest();
        $req->setId($ticket->getId());
        $res = MockHostProvider::execute($req);
        $this->assertEquals($res->getTicket()->getPriceCurrency(), $ticket->getPriceCurrency());
    }

    public function testFindTicket()
    {
        $ticket = $this->createTicket();
        $req = new FindTicketRequest();
        
        $res = MockHostProvider::execute($req);
        $this->assertTrue(count($res->getTickets()) > 0);
    }

    protected function createTicket()
    {
        $ticket = new Ticket();
        $ticket->setPriceCurrency(2000);
        $this->ticketRepo->insert($ticket);
        return $ticket;
    }
}