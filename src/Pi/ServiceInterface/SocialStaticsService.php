<?hh

namespace Pi\ServiceInterface;

use Pi\Service,
	Pi\Common\ClassUtils,
	Pi\Common\Http\HttpRequest,
	Pi\Odm\MongoRepository,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\ILog;


/**
 * @name Social Statics Provider
 * @description
 * Collects likes, shares and other relevant data from social providers and saves in Pi
 * Data is collected with queue jobs in intervals
 */
class SocialStaticsService implements IContainable {

	const NAME = 'Pi\ServiceInterface\WordpressCrawler';

	public function __construct(
		protected ILog $logger
		)
	{

	}

	public function ioc(IContainer $ioc) {}
	

	public static function update(MongoRepository $repository, int $skip = 0, int $take = 10)
	{

		$data = $repository->queryBuilder()
			->find()
			->getQuery()
			->execute();

		foreach ($data as $key => $value) {
			
			//die(print_r($value));
		}
	}

	public function getShares(string $contentUrl)
	{
		$links = array(
			"facebook"    => "https://api.facebook.com/method/links.getStats?format=json&urls=",
            "twitter"     => "http://urls.api.twitter.com/1/urls/count.json?url=",
            "google"      => "https://plusone.google.com/_/+1/fastbutton?url=",
            "linkedin"    => "https://www.linkedin.com/countserv/count/share?format=json&url=",
            "pinterest"   => "http://api.pinterest.com/v1/urls/count.json?url=",
            "stumbleupon" => "http://www.stumbleupon.com/services/1.01/badge.getinfo?url=",
            "delicious"   => "http://feeds.delicious.com/v2/json/urlinfo/data?url=",
            "reddit"      => "http://www.reddit.com/api/info.json?&url=",
            "buffer"      => "https://api.bufferapp.com/1/links/shares.json?url=",
            "vk"          => "https://vk.com/share.php?act=count&index=1&url="
        );
        $counts = Map{};

        foreach($links as $provider => $providerUrl) {
        	try {
        		$count = $this->getCount($provider, $providerUrl . $contentUrl);
        		$counts->add(Pair{$provider, $count});
        	}
        	catch(\Exception $ex) {
        		$this->logger->error($ex->getMessage());
        	}	
        }

        return $counts;
	}

	public function getCount(string $provider, string $requestUrl)
	{
		$request = new HttpRequest($requestUrl, HttpRequest::METHOD_GET);
	  	$message = $request->send();
	  	$count = 0;
	  	$data = $message->getBody();

	  	switch ($provider) {
	  		case "facebook":
	  			$data = json_decode($data);
	  			$count = (is_array($data) ? $data[0]->total_count : $data->total_count);
	  			break;
	  		case "google":
                preg_match( '/window\.__SSR = {c: (\d+(?:\.\d+)+)/', $data, $matches);
				if(isset($matches[0]) && isset($matches[1])) {
					$bits = explode('.',$matches[1]);
					$count = (int)( empty($bits[0]) ?: $bits[0]) . ( empty($bits[1]) ?: $bits[1] ); 
				}
				break;
			case "pinterest":
                $data = substr( $data, 13, -1);
            case "linkedin":
            case "twitter":
                $data = json_decode($data);
                $count = $data->count;
                break;
            case "stumbleu	pon":
                $data = json_decode($data);
                $count = $data->result->views;
                break;
            case "delicious":
                $data = json_decode($data);
                $count = $data[0]->total_posts;
                break;
            case "reddit":
                $data = json_decode($data);
                foreach($data->data->children as $child) {
                    $ups+= (int) $child->data->ups;
                    $downs+= (int) $child->data->downs;
                }
                $count = $ups - $downs;
                break;
            case "buffer":
                $data = json_decode($data);
                $count = $data->shares;
                break;
            case "vk":
                $data = preg_match('/^VK.Share.count\(\d+,\s+(\d+)\);$/i', $data, $matches);
                $count = $matches[1];
                break;
	  		default:
	  			throw new \Exception(
	  				sprintf('The provider %s isnt supported', $provider)
	  				);
	  			break;
	  	}

	  	return $count;
	}
}