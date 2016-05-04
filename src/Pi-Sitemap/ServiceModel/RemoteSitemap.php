<?hh

namespace Pi\Sitemap\ServiceModel;

class RemoveSitemap {
	
	public function __construct(
		protected \MongoId $id
	)
	{

	}

	public function getId() : \MongoId
	{
		return $this->id;
	}
}