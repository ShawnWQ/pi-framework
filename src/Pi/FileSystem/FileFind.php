<?hh

namespace Pi\FileSystem;

class FileFind {

	protected $ownerId;

	protected int $skip = 0;

	protected int $limit = 10;

	<<Int>>
	public function getSkip() : int
	{
		return $this->skip;
	}

	public function setSkip(int $limit) : void
	{
		return $this->skip;
	}

	<<Int>>
	public function getLimit() : int
	{
		return $this->limit;
	}

	public function setLimit(int $limit) : void
	{
		return $this->limit;
	}

	public function setOwnerID($id)
	{
		$this->ownerId= $id;
	}

	public function getOwnerId()
	{
		return $this->ownerId;
	}
}
