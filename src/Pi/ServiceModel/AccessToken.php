<?hh

namespace Pi\ServiceModel;

class AccessToken {

	protected string $accessToken;

	protected string $refreshToken;

	protected $userId;

	protected $clientId;

	protected string $scope;

	protected int $expiresIn;

	protected $createdAt;
}