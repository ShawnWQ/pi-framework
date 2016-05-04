<?hh

namespace Pi\FileSystem;

<<Request>>
class FileUpload {

	protected $password;

	protected $file;

	protected $displayName;

	protected $description;

	protected $url;

	protected $fileName;

	protected $exception;

	protected $extension;

	protected $generateThumbnail;

	public function __construct()
	{

	}

	<<File>>
	public function file($value = null)
	{
		if($value === null) return $this->file;
		$this->file = $value;
	}

	<<Bool>>
	public function generateThumbnail($value = null)
	{
		if($value === null) return $this->generateThumbnail;
		$this->generateThumbnail = $value;
	}

	<<String>>
	public function description($value = null)
	{
		if($value === null) return $this->description;
		$this->description = $value;
	}

	<<String>>
	public function extension($value = null)
	{
		if($value === null) return $this->extension;
		$this->extension = $value;
	}

	<<String>>
	public function url($value = null)
	{
		if($value === null) return $this->url;
		$this->url = $value;
	}

	<<String>>
	public function displayName($value = null)
	{
		if($value === null) return $this->displayName;
		$this->displayName = $value;
	}

	<<String>>
	public function password($value = null)
	{
		if($value === null) return $this->password;
		$this->password = $value;
	}


}
