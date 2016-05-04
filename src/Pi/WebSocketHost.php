<?hh

namespace Pi;
use Pi\Interfaces\IPiHost;
use Pi\Interfaces\IContainer;

abstract class WebSocketHost extends PiHost {

  protected $clients = array();

  public function start()
  {

  }

  public function run()
  {
    
  }

  public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
