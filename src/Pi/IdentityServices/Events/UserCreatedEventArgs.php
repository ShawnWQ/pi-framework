<?hh

namespace Pi\IdentityServices\Events;

class UserCreatedEvents extends EventArgs {

  public function __construct($invoker, $userDto)
  {
    $this->invoker = $invoker;
    $this->user = $userDto;
  }

  public function getInvoker()
  {
    return $this->invoker;
  }

  public function getUser()
  {
    return $this->user;
  }

  protected $invoker;
  protected $user;
}
