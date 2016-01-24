<?hh

use Pi\PiHost;

namespace Pi;

/**
 * AppHost to listen redis and others sockets like services itself
 * The implementations will call $messageService->start() at the end to pen the socket
 */
abstract class PiHostListenerBase
	extends PiHost {

		protected $threadsPerProcessor = 16;


		public function poolSize() : int
		{
			$count = 1; // read processor count from environment
			return $count * $this->threadsPerProcessor;
		}

		public function processRequestAsync($httListenerContext)
		{

		}
	}
