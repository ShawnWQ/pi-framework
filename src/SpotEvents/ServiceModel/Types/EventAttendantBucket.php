<?hh

namespace SpotEvents\ServiceModel\Types;

use Pi\Odm\BucketCollection;

class EventAttendantBucket extends BucketCollection{
	
	protected $data;

    /**
     * Gets the value of data.
     *
     * @return mixed
     */
    <<EmbedMany('SpotEvents\ServiceModel\Types\EventAttendant')>>
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