<?hh

namespace Pi\Sitemap\ServiceModel;

<<Collection('sitemap')>>

class Sitemap {
	
	protected string $uri;
	
	protected ?string $imageSrc;
	
	protected ?string $imageCaption;
	

	public function getUri() : string
	{
		return $this->uri;
	}

	public function setUri(string $value) : void
	{
		$this->uri = $value;
	}

	public function getImageSrc() : ?string
	{
		return $this->imageSrc;
	}

	public function setImageSrc(string $value) : void
	{
		$this->imageSrc = $value;
	}

	public function getImageCaption() : ?string
	{
		return $this->imageCaption;
	}

	public function setImageCaption(string $value) : void
	{
		$this->imageCaption = $value;
	}
}