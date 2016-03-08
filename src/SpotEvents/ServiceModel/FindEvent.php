<?hh

namespace SpotEvents\ServiceModel;
use Pi\Interfaces\IRequestHasLimit;
use Pi\Interfaces\IRequestHasSort;
class FindEvent implements IRequestHasSort, IRequestHasLimit {

	protected $skip;

	protected $limit;

	protected $sortBy;

	protected $sortOrder;

	protected $upcoming;

    protected ?string $categoryId;

	<<Bool>>
	public function getUpcoming()
	{
		return $this->upcoming;
	}

	public function setUpcoming(bool $value)
	{
		$this->upcoming = $value;
	}

    /**
     * Gets the value of skip.
     *
     * @return mixed
     */
    <<Int>>
    public function getSkip()
    {
        return $this->skip;
    }

    /**
     * Sets the value of skip.
     *
     * @param mixed $skip the skip
     *
     * @return self
     */
    public function setSkip($skip)
    {
        $this->skip = $skip;

        return $this;
    }

    /**
     * Gets the value of limit.
     *
     * @return mixed
     */
    <<Int>>
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Sets the value of limit.
     *
     * @param mixed $limit the limit
     *
     * @return self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Gets the value of sortBy.
     *
     * @return mixed
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * Sets the value of sortBy.
     *
     * @param mixed $sortBy the sort by
     *
     * @return self
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * Gets the value of sortOrder.
     *
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Sets the value of sortOrder.
     *
     * @param mixed $sortOrder the sort order
     *
     * @return self
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getCategoryId() : ?string
      {
        return $this->categoryId;
      }

      public function setCategoryId(string $value) : void
      {
        $this->categoryId = $value;
      }
}
