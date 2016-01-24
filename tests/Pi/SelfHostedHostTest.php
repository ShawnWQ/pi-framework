<?hh
use Pi\SelfHostedHost;
use Pi\Interfaces\IContainer;

class SelfHostedMock extends SelfHostedHost {

	public function configure(IContainer $ioc)
	{
		/*
		 var mqServer = container.Resolve<IMessageService>();

        mqServer.RegisterHandler<AuthOnly>(m => {
            var req = new BasicRequest { Verb = HttpMethods.Post };
            req.Headers["X-ss-id"] = m.GetBody().SessionId;
            var response = ServiceController.ExecuteMessage(m, req);
            return response;
        });

        //Start the Rabbit MQ Server listening for incoming MQ Requests
        mqServer.Start();
        */
	}
}
class SelfHostedHostTest extends \PHPUnit_Framework_TestCase {
	
	protected $host;

	public function setUp()
	{
		$this->host = new SelfHostedMock();
	}

	public function testCanListenForIncomingRequests()
	{
		$this->host->init();
		/*$this->host->start(async function(resource $client){
		  await SelfHostedMock::onResponse($client, async function(string $data, resource $client){
		    die("Client Sent Data: $data\n");
		  });
		});*/
	}
}