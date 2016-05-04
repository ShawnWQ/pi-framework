<?hh

namespace Pi\Realtime\ServiceInterface\Interfaces;

interface ILoop {
	/**
     * Perform a single iteration of the event loop.
     */
    public function tick() : void;
    /**
     * Run the event loop until there are no more tasks to perform.
     */
    public function run() : void;
    /**
     * Instruct a running event loop to stop.
     */
    public function stop() : void;
}