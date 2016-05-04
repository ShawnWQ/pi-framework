<?hh

namespace Pi\Realtime\ServiceInterface;
use Pi\Realtime\ServiceInterface\Interfaces\IWritabbleStream;

class Buffer implements IWritabbleStream {

	protected $listening = false;

	public function __construct(
		protected $stream)
	{

	}

	public function isWritable() : bool
	{

	}

    public function write($data) : void
    {

    }

    public function end($data = null) : void
    {

    }

    public function close() : void
    {

    }
}