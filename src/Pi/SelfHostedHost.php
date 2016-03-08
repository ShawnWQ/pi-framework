<?hh
namespace Pi; 
/**
 *
 * https://github.com/steelbrain/HHVM-Async-Server
 */
abstract class SelfHostedHost extends AppHost {
	public resource $socket;

	protected int $port = 2354;

	protected string $address = 'localhost';

	public function __construct()
	{
		parent::__construct();
		$socket = $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	    register_shutdown_function(function() use ($socket){
	      if(is_resource($socket)) fclose($socket);
	    });
	}

	public function close():void{
		fclose($this->socket);
	}
	

	public async function start((function(resource):Awaitable<void>) $callback):Awaitable<void>{
		return;
	    if(!is_resource($this->socket)){
	      // It's closed...
	      return ;
	    }
	    socket_bind($this->socket, $this->address, $this->port);
	    socket_listen($this->socket, 100);
	    $handles = Vector{};
	    while(is_resource($this->socket)){
	      try {
	        $handles->add($callback(socket_accept($this->socket))->getWaitHandle());
	        if($handles->count() === 2){
	          await GenVectorWaitHandle::create($handles);
	          $handles->clear();
	        }
	      } catch(Exception $e){} // Let the server run!
	    }
  }
 
  public static async function onResponse(resource $socket, (function(string, resource):Awaitable<void>) $callback):Awaitable<void> {
 
    while (is_resource($socket) && !feof($socket)){
      $select = await stream_await($socket, STREAM_AWAIT_READ, 5);
      switch ($select) {
        case STREAM_AWAIT_READY:
          $data = stream_get_contents($socket);
          if(!is_bool($data)){
            await $callback($data, $socket);
          }
          break;
        case STREAM_AWAIT_CLOSED:
        return ;
        // Do nothing for timeouts
        default:
      }
    }
  }

  public async function listenInCoop((function(resource):Awaitable<void>) $first, (function(SelfHostedHost):Awaitable<void>) $second):Awaitable<void>{
    await GenArrayWaitHandle::create([$second($this), $this->start($first)]);
  }	
}