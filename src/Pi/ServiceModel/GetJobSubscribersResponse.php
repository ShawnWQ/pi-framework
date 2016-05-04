<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetJobSubscribersResponse extends Response {

	protected $users;

	public function getUsers()
	{
		return $this->users;
	}

	public function setUsers($data)
	{
		$this->users = $data;
	}
}