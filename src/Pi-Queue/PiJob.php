<?hh

namespace Pi\Queue;




class PiJob {

	
	protected PiWorker $worker;

	protected $instance;

	public function __construct(
		protected PiQueue $piQueue,
		protected $queue, 
		protected $payload
	)
	{

	}
	
	public function setWorker(PiWorker $worker)
	{
		$this->worker = $worker;
	}

	public function getQueue()
	{
		return $this->queue;
	}

	public function getPayload()
	{
		return $this->payload;
	}

	public function create(string $queue, string $class, string $request, $dto = null, ?string $id = null) : string
	{
		
	}

	public function perform()
	{
		try {
			$svc = $this->getService();
			die('call svc request');
		}
		catch(PiJobDontPerformException $ex) {
					// beforePerform/setUp have said don't perform this job. Return.
			return false;
		}
		return true;
	}

	public function getService()
	{

	}

	public function reserve(string $queue)
	{
		
	}

	public function updateStatus($status)
	{
		if(empty($this->payload['id'])) {
			return;
		}

		//
	}

	public function fail(\Exception $exception)
	{
		$this->updateStatus(JobStatus::StatusFailed);
	}

	public function __toString()
	{
		$name = array(
			'Job{' . $this->queue .'}'
		);
		if(!empty($this->payload['id'])) {
			$name[] = 'ID: ' . $this->payload['id'];
		}
		$name[] = $this->payload['class'];
		if(!empty($this->payload['dto'])) {
			$name[] = json_encode($this->payload['dto']);
		}
		return '(' . implode(' | ', $name) . ')';
	}
}