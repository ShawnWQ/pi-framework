<?hh

namespace Pi\Host;
use Pi\Service;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;

class MetadataService extends Service {

	
	public function __construct(protected ICacheProvider $cacheProvider)
	{

	}

	public function onAfterInit(IRequest $httpRequest, IResponse $httpResponse)
	{

	}

	public function get()
	{
		return
	}
}

class MetadataServiceGetResponse {
	
	public function __construct(Vector ServiceMeta $services, $lastAppVersion = '0.0.1')
	{

	}
}

class MetadataServiceGet {
	
	public function __construct(protected string serviceName)
	{

	}

	public function serviceName() : string
	{
		return $this->serviceName;
	}
}