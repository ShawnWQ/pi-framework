<?hh

namespace Pi\ServiceModel;
use Pi\Response;

class MetadataGetResponse extends Response{

	public function __construct(protected $services)
	{

	}

	public function getServices()
	{
		return $this->services;
	}
}