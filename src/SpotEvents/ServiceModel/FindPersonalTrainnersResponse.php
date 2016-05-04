<?hh

namespace SpotEvents\ServiceModel;
use Pi\Response;
class FindPersonalTrainnersResponse extends Response{
	
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