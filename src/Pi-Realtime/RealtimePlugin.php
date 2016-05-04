<?hh

namespace Pi\Realtime;
use Pi\Plugin;
use Pi\Interfaces\IContainer;
use Pi\Realtime\ServicesInterface\ChatService;

class WsHost {

	protected $stream;

	protected string $hostname = 'localhost';

	protected $timeout = 240;

	protected $context = array();

	protected $session;

	public function connect()
	{
		if(is_resource($this->stream)) {
			return;
		}

		$errors = [null, null];

		$this->stream = stream_socket_client($this->hostname, $errors[0], $errors[1], $this->timeout, STREAM_CLIENT_CONNECT, stream_context_create($this->context));

		 $this->stream = @stream_socket_server("tcp://$host:$port", $errno, $errstr);
        if (false === $this->stream) {
            $message = "Could not bind to tcp://$host:$port: $errstr";
            throw new ConnectionException($message, $errno);
        }
        stream_set_blocking($this->stream, 0);
        $this->loop->addReadStream($this->stream, function ($stream) {
            $newSocket = stream_socket_accept($stream);
            if (false === $newSocket) {
                $this->emit('error', array(new \RuntimeException('Error accepting new connection')));
                return;
            }
            $this->handleConnection($newSocket);
        });
	}

	public function handleConnection($socket)
    {
        stream_set_blocking($socket, 0);
        $client = $this->createConnection($socket);
        $this->emit('connection', array($client));
    }


	public function write($code, $message = null)
	{
		if(!is_resource($this->stream)) {
			return;
		}

		$payload = '';
		$bytes = fwrite($this->stream, $payload);
		return $bytes;
	}

	public function close()
    {
        if (!is_resource($this->stream)) {
            return;
        }
        fclose($this->stream);
        $this->stream = null;
        $this->session = null;
    }
}
class RealtimePlugin extends Plugin {

  public function configure(IContainer $container)
  {
      $container->registerService(new ChatService());
  }
}
