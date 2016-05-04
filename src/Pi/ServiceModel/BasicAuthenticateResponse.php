<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class BasicAuthenticateResponse extends Response{

  public function __construct(protected string $accessToken, protected \MongoId $userId)
  {

  }

  public function jsonSerialize()
  {
    $vars = get_object_vars($this);
    $vars['userId'] = (string)$vars['userId'];
    return $vars;
  }

  public function getUserId()
  {
  	return $this->userId;
  }

  public function getAccessToken()
  {
    return $this->accessToken;
  }
}
