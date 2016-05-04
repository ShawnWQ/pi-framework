<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\BucketCollection;

class NewsletterSubscriptionBucket extends BucketCollection{
	
	protected $data;

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    <<EmbedMany('Pi\ServiceModel\Types\NewsletterSubscription')>>
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