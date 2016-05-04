<?hh

namespace Pi\ServerEvents;

class SubscriptionInfo {

  protected $createdAt;

  protected $channels;

  protected $userId;

  protected $userName;

  protected $displayName;

  protected $sessionId;

  protected $subscriptionId;

  protected $isAuthenticated;

  protected $meta;

  public function createdAt($value = null)
  {
    if($value === null) return $this->createdAt;
    $this->createdAt = $value;
  }

  public function channels($value = null)
  {
    if($value === null) return $this->channels;
    $this->channels = $value;
  }

  public function userId($value = null)
  {
    if($value === null) return $this->userId;
    $this->userId = $value;
  }

  public function userName($value = null)
  {
    if($value === null) return $this->userName;
    $this->userName = $value;
  }

  public function displayName($value = null)
  {
    if($value === null) return $this->displayName;
    $this->displayName = $value;
  }

  public function sessionId($value = null)
  {
    if($value === null) return $this->sessionId;
    $this->sessionId = $value;
  }

  public function subscriptionId($value = null)
  {
    if($value === null) return $this->subscriptionId;
    $this->subscriptionId = $value;
  }

  public function isAuthenticated($value = null)
  {
    if($value === null) return $this->isAuthenticated;
    $this->isAuthenticated = $value;
  }

  public function meta($value = null)
  {
    if($value === null) return $this->meta;
    $this->meta = $value;
  }
}
