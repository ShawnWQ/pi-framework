<?hh

namespace Pi\Realtime\ServicesInterface\Interfaces;

interface IWritabbleStream {

	public function isWritable() : bool;
    public function write($data) : void;
    public function end($data = null) : void;
    public function close() : void;
}