<?hh

namespace Pi\RealTime\ServicesInterface\Interfaces;

interface IServer {

	public function listen($port, $host = '127.0.0.1');
    public function getPort();
    public function shutdown();
    
}