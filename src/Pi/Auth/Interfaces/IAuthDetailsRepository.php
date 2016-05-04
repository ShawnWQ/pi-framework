<?hh


namespace Pi\Auth\Interfaces;

interface IAuthDetailsRepository {

	function getTokenById($id) : ?string;

	function getAuthByToken(string $token) : ?UserAuthDetails;
}