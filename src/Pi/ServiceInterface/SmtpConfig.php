<?hh

namespace Pi\ServiceInterface;

class SmtpConfig {

  public function __construct(protected string $host = 'localhost', protected int $port = 587,
    protected ?string $userName = null,
    protected ?string $password)
    {

    }
    public function getHost() : string
    {
      return $this->host;
    }

    public function getPort() : int
    {
      return $this->port;
    }

    public function getUserName() : ?string
    {
      return $this->userName;
    }

    public function getPassword() : ?string
    {
      return $this->password;
    }

}
