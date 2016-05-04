<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class FindUserResponse extends Response {

    protected $users;

    public function getUsers()
    {
      return $this->users;
    }

    public function setUsers($users)
    {
      $this->users = $users;
    }
}
