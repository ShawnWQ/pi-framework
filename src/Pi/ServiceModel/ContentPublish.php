<?hh

namespace Pi\ServiceModel;

class ContentPublish {

	public function __construct(
		protected string $title,
		protected string $content,
		protected ?string $url,
		protected ?string $image,
		protected ?string $excerpt
	)
	{

	}

	public function getTitle() : string
	{
		return $this->title;
	}

	public function getContent() : string
	{
		return $this->content;
	}

	public function getUrl() : ?string
	{
		return $this->url;
	}

	public function getImage() : ?string
	{
		return $this->image;
	}

	public function getExcerpt() : ?string
	{
		return $this->excerpt;
	}
}