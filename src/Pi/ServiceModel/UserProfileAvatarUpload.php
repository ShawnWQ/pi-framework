<?hh

namespace Pi\ServiceModel;

class UserProfileAvatarUpload {
	
	protected $file;

	protected ?\MongoId $id;

	<<ObjectId>>
	public function getId() : ?\MongoId
	{
		return $this->id;
	}

	public function setId(\MongoId $id) : void
	{
		$this->id = $id;
	}
	
	<<File>>
	public function file($value = null)
	{
		if($value === null) return $this->file;
		$this->file = $value;
	}
}