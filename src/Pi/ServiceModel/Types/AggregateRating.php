<?hh

namespace Pi\ServiceModel\Types;

class AggregateRating extends Rating {
    /**
     * @return mixed
     */
    public function getReviewCount()
    {
        return $this->reviewCount;
    }

    /**
     * @param mixed $reviewCount
     */
    public function setReviewCount($reviewCount)
    {
        $this->reviewCount = $reviewCount;
    }

    /**
     * @return mixed
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    /**
     * @param mixed $ratingCount
     */
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;
    }

    /**
     * @return mixed
     */
    public function getItemReviewed()
    {
        return $this->itemReviewed;
    }

    /**
     * @param mixed $itemReviewed
     */
    public function setItemReviewed($itemReviewed)
    {
        $this->itemReviewed = $itemReviewed;
    }
	
	/**
	 * The item that is being reviewed/rated.
	 */
	protected $itemReviewed;

	/**
	 * The count of total number of ratings.
	 */
	protected $ratingCount;

	/**
	 * The count of total number of reviews.
	 */
	protected $reviewCount;
}