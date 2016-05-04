<?hh

namespace Pi\Realtime\ServicesInterface\Interfaces;

interface IReadableStream {

	public function isReadable();
    public function pause();
    public function resume();
    public function pipe(IWritabbleStream $dest, array $options = array());
    public function close();
}