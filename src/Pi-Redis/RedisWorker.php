<?hh

namespace Pi\Redis;

use Pi\PiHost;
use Pi\Interfaces\IContainer,
	Pi\Queue\PiWorker;


class RedisWorker extends PiHost {

	protected int $port = 2354;

	protected string $address = 'localhost';

	public resource $socket;
	
	public function __construct()
	{
		parent::__construct();
		$socket = $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	    register_shutdown_function(function() use ($socket){
	      if(is_resource($socket)) fclose($socket);
	    });
	}

	public function afterInit()
	{
		
	}

	public function configure(IContainer $container)
	{

	}

	public function close() :void
	{
		fclose($this->socket);
	}

	public function start()
	{
		if(!is_resource($this->socket)){
	      return ;
	    }
	    socket_bind($this->socket, $this->address, $this->port);
	    socket_listen($this->socket, 100);
	    $handles = Vector{};
	    while(is_resource($this->socket)){
	    	$queue = $this->container->get('Pi\Queue\PiQueue');
	    	$factory = $this->container()->get('Pi\Interfaces\ILogFactory');
    		$logger = $factory->getLogger(get_class($queue));
    		$redis = $this->container->get('IRedisClientsManager');
	    	$worker = new PiWorker($queue, array('default'), $logger, $redis);
	    	$worker->work(5, false);
	    }
	}


}