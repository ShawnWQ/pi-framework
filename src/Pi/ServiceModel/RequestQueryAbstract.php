<?hh

namespace Pi\ServiceModel;
use Pi\Interfaces\IRequestHasLimit;
use Pi\Interfaces\IRequestHasSort;

abstract class RequestQueryAbstract implements IRequestHasSort, IRequestHasLimit {

    protected $skip = 0;

    protected $limit = 10;

    protected $sortBy;

    protected $sortOrder;

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
}