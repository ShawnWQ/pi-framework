<?hh

namespace Pi\Sitemap\ServiceModel;

class PublishSitemapEntry {
	
	public function __construct(
		protected string $uri,
		protected ?string $imageSrc,
		protected ?string $imageCaption
	)
	{

	}

	public function getUri() : string
	{
		return $this->uri;
	}

	public function getImageSrc() : ?string
	{
		return $this->imageSrc;
	}

	public function getImageCaption() : ?string
	{
		return $this->imageCaption;
	}
}