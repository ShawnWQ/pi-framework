<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class PostRequestFriendshipResponse extends Response {

		protected UserDto $requested;

		public function setRequested(UserDto $user)
		{
			$this->requested = $user;
		}
}
