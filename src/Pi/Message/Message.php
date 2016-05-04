<?hh

namespace Pi\Message;
use Pi\Interfaces\IMessage;
use Pi\ServiceModel\ResponseStatus;
use Pi\ServiceModel\ResponseError;

class Message
  implements IMessage {

    protected $body;

    protected ?ResponseStatus $error;

    public function setBody($body)
    {
      $this->body = $body;
    }
    /**
     * The Request DTO is embeded in Body
     */
    public function body()
    {
      return $this->body;
    }
    /**
     * Date when the message was created
     */
    public function createdDate()
    {

    }

    public function error()
    {
      return $this->error;
    }

    public function setError(ResponseStatus $error)
    {
      $this->error = $error;
    }

    public function options()
    {

    }

    /**
     * Queue priority
     */
    public function priority()
    {

    }

    public function replyId()
    {

    }

    public function replyTo()
    {

    }

    /**
     * Number of reply attempts
     */
    public function replyAttempts()
    {

    }

    public function tag()
    {

    }
  }
