<?hh

namespace Pi\ServiceInterface;
use Pi\Service;
use Pi\Host\HostProvider;
use Pi\ServiceModel\MetadataGet;
use Pi\ServiceModel\MetadataGetResponse;
use Pi\Interfaces\ICacheProvider;

class MetadataService extends Service {

	public ICacheProvider $cache;

	<<Request,Route('/meta/services'),Method('GET')>>
	public function get(MetadataGet $request)
	{
		$meta = HostProvider::servicesMetadata();
		$data = $meta->getServicesTypes();
		$response = new MetadataGetResponse($data);
		return $response;
	}
}
