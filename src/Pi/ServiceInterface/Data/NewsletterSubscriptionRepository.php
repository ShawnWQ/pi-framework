<?hh

namespace Pi\ServiceInterface\Data;

class NewsletterSubscriptionRepository extends BucketRepository<TNewsletterSubscription> {

  protected $data;

  /**
   * Gets the value of data.
   *
   * @return mixed
   */
  <<EmbedMany('Pi\ServiceModel\Types\Subscription')>>
  public function getData()
  {
      return $this->data;
  }

  /**
   * Sets the value of data.
   *
   * @param mixed $data the data
   *
   * @return self
   */
  public function setData($data)
  {
      $this->data = $data;

      return $this;
  }
}
