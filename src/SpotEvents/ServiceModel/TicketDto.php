<?hh

namespace SpotEvents\ServiceModel;
use Pi\Odm\Interfaces\IEntity;
use Pi\ServiceModel\Types\Thing;
use Pi\ServiceModel\Types\Author;
use Pi\ServiceModel\Types\Seat;

<<Collection("Tickets")>>
class TicketDto implements IEntity, \JsonSerializable {

	protected \MongoId $id;

    /**
     * An alias for the item.
     */
    protected string $alternateName;

    protected string $description;

    protected $image;

    protected string $name;

    protected string $url;
    
    protected \DateTime $dateIssued;

    protected $issuedBy;

    protected $priceCurrency;

    protected $ticketNumber;

    /**
     * Reference to an asset (e.g., Barcode, QR code image or PDF) usable for entrance.
     */
    protected $ticketToken;

    protected Seat $ticketSeat;

    protected $totalPrice;

    /**
     * The person or organization the reservation or ticket is for.
     */
    protected $underName;

    protected Author $author;

    protected \MongoId $paymentId;

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    <<Id>>
    public function id($value = null)
    {
        if($value === null) return $this->id;
        $this->id = $value;
    }

    <<EmbedOne('Pi\ServiceModel\Types\Author')>>
    public function getAuthor()
    {

    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }
    /**
     * @return mixed
     */
    <<String>>
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    <<EmbedOne('Pi\ServiceModel\Types\Author')>>
    public function getIssuedBy()
    {
        return $this->issuedBy;
    }

    /**
     * @param mixed $issuedBy
     */
    public function setIssuedBy($issuedBy)
    {
        $this->issuedBy = $issuedBy;
    }

    /**
     * @return mixed
     */
    <<Decimal>>
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * @param mixed $priceCurrency
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return mixed
     */
    <<String>>
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    /**
     * @param mixed $ticketNumber
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * @return mixed
     */
    <<String>>
    public function getTicketToken()
    {
        return $this->ticketToken;
    }

    /**
     * @param mixed $ticketToken
     */
    public function setTicketToken($ticketToken)
    {
        $this->ticketToken = $ticketToken;
    }

    /**
     * @return mixed
     */
    <<Decimal>>
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return mixed
     */
    <<String>>
    public function getUnderName()
    {
        return $this->underName;
    }

    /**
     * @param mixed $underName
     */
    public function setUnderName($underName)
    {
        $this->underName = $underName;
    }

    <<ObjectId>>
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function setPaymentId(\MongoId $id)
    {
        $this->paymentId = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $image
     */
    public function setId($value)
    {
        $this->id = $value;
    }
}