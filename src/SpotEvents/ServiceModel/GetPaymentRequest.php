<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 5:42 AM
 */

namespace SpotEvents\ServiceModel;


class GetPaymentRequest {
    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param mixed $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * @return \MongoId
     */
    <<ObjectId>>
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \MongoId $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setReference(string $value)
    {
        $this->reference = $value;
    }

    public function getReference()
    {
        return $this->reference;
    }

    protected ?string $reference;

    protected $authorId;

    /**
     * @var \MongoId
     */
    protected $id;
}