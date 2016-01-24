<?hh

namespace Pi\ServiceModel;

class BasicAuthenticateRequest {

  protected string $email;
  
  protected string $password;

  public function __construct()
  {

  }

  public function setEmail($value)
  {
    $this->email = $value;
  }

  public function setPassword($value)
  {
    $this->password = $value;
  }

  <<String>>
  public function getEmail()
  {
    return $this->email;
  }

  <<String>>
  public function getPassword()
  {
    return $this->password;
  }
}
