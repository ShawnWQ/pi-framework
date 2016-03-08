<?hh

namespace Pi\ServerEvents\ServiceInterface;

use Pi\Service;
use Pi\ServerEvents\ServiceModel\UnRegisterEventSubscriber;
use Pi\ServerEvents\ServiceModel\RegisterRequest;

class ServerEventsUnRegisterService extends Service {

    protected $handlers;
    public $exec_limit = 600;

    protected $id;

    public $sleep_time = 0.5;
	<<Request,Route('/event-register')>>
	public function register(RegisterRequest $request)
	{

	}

  public function any(UnRegisterEventSubscriber $request)
  {
    
  }
}
