<?hh

namespace Pi\Queue;

use Pi\Interfaces\ILog,
	Pi\Redis\Interfaces\IRedisClient;



class PiWorker {
	/**
	 * @var boolean True if on the next iteration, the worker should shutdown
	 */
	private $shutdown = false;
	/**
	 * @var boolean True if this worker is paused.
	 */
	private $paused = false;
	/**
	 * @var string String identifying this worker.
	 */
	private $id;
	/**
	 * @var Resque_Job Current job, if any, being processed by this worker.
	 */
	private $currentJob = null;
	/**
	 * @var int Process ID of child worker processes.
	 */
	private $child = null;

	private $hostname;

	const NAME = 'Pi\Queue\PiWorker';

	 /**
     * Instantiate a new worker, given a list of queues that it should be working
     * on. The list of queues should be supplied in the priority that they should
     * be checked for jobs (first come, first served)
     *
     * Passing a single '*' allows the worker to work on all queues in alphabetical
     * order. You can easily add new queues dynamically and have them worked on using
     * this method.
     *
     * @param string|array $queues String with a single queue name, array with multiple.
     */
	public function __construct(
		protected PiQueue $piQueue,
		protected array $queues,
		protected ILog $logger,
		protected IRedisClient $redis
	)
	{
		if(function_exists('gethostname')) {
            $hostname = gethostname();
        }
        else {
            $hostname = php_uname('n');
        }
        $this->hostname = $hostname;
		$this->id = $this->hostname . ':'.getmypid() . ':' . implode(',', $this->queues);
	}

	public function init()
	{

	}

	public function registerWorker()
	{
		$this->redis->sadd('workers', (string)$this);
		$this->redis->set('worker::' . (string)$this . '::started', strftime('%a %b %d %H:%M:%S %Z %Y'));
	}

	public function unregisterWorker()
	{
		if(is_object($this->currentJob)) {
			$this->currentJob->fail(new \Exception());
		}

		$id = (string)$this;
		$this->redis->srem('workers', $id);
		$this->redis->del('worker::' . $id);
		$this->redis->del('worker::' . $id . '::started');
	}

	public function workerPids() : array
	{
		$pids = array();
		exec('ps -A -o pid,command | grep [p]ijob', $cmdOutput);
		foreach ($cmdOutput as $line) {
			list($pids[],) = explode(' ', trim($line), 2);
		}
		return $pids;
	}

	public function workingOn(PiJob $job)
	{
		$job->setWorker($this);
		$this->currentJob = $job;
		$job->updateStatus(JobStatus::StatusRunning);
		$data = json_encode(array(
			'queue' => $job->getQueue(),
			'run_at' => strftime('%a %b %d %H:%M:%S %Z %Y'),
			'payload' => $job->getPayload()
			)
		);
	}

	public function doneWorking()
	{
		$this->currentJob = null;
		$this->redis->del('worker::' . (string)$this);
	}

	public function job()
	{
		$job = $this->redis->get('worker::' . (string)$this);
		if(!$job) {
			return array();
		}
		else {
			return json_decode($job, true);
		}
	}

	public function startup() : void
	{
		$this->registerWorker();
	}

	public function work($interval = PiQueue::DEFAULT_INTERVAL, $blocking = false)
	{
		$this->startup();
		while(true) {
			if($this->shutdown) {
				break;
			}

			// attempt to find and reserve a job
			$job = false;

			if(!$this->paused) {
				if($blocking === true) {
					$this->logger->info('Starting blocking with interval of $interval');
				}

				$job = $this->reserve($blocking, $interval);
			}

			if(!$job) {
				
				if($interval == 0) { // For an interval of 0, break now - helps with unit testing etc
					break;
				}

				if($blocking === false) {
					$this->logger->info('Sleeping for $interval');
					if($this->paused) {
						// set updated
					} else {
						// set waiting for this->queues
					}
					usleep($interval * 1000000);
				}

				continue;
			}

			$this->logger->info('starting work on $job');
			$this->workingOn($job);
			$this->perform($job);
		}

	}

	public function perform(PiJob $job)
	{
		try {
			$job->perform();
		}
		catch(\Exception $ex) {
			$this->logger->fatal('$job has failed ' . $ex->getMessage());
			$job->fail($ex);
			return;
		}

		$job->updateStatus(JobStatus::StatusComplete);
		$this->logger->info('$job has finished');
	}

	public function reserve($blocking = false, $timeout = null)
	{
		$queues = $this->queues();
		if(!is_array($queues)) {
			return;
		}

		foreach ($queues as $queue) {
			$this->logger->info('Checking $queues for jobs');
			$payload = $this->piQueue->pop($queue);
			
			if(!is_array($payload)) {
				continue;
			}
			$job = new PiJob($this->piQueue, $queue, $payload);
			$this->logger->info('Found job $job');
			return $job;
		}

		return false;
	}

	public function queues($fetch = true)
	{
		if(!in_array('*', $this->queues) || $fetch == false) {
			return $this->queues;
		}
		$queues = $this->piQueue->queues();
		sort($queues);
		return $queues;
	}

	public function shutdown()
	{
		$this->shutdown = true;
		$this->logger->info('Shutting down');
	}

	public function __toString()
	{
		return $this->id;
	}
}