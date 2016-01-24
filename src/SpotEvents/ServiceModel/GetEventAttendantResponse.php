<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;

class GetEventAttendantResponse extends Response {
	
	protected $users;


    /**
     * Gets the value of users.
     *
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Sets the value of users.
     *
     * @param mixed $users the users
     *
     * @return self
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }
}