<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\BucketCollection;

class CommentBucket extends BucketCollection {

  protected string $namespace;

	protected $data;

  <<String>>
  public function getNamespace()
  {
    return $this->namespace;
  }

  public function setNamespace(string $value)
  {
    $this->namespace = $value;
  }

  /**
   * Gets the value of data.
   *
   * @return mixed
   */
  <<EmbedMany('Pi\ServiceModel\Types\CommentMessage')>>
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
