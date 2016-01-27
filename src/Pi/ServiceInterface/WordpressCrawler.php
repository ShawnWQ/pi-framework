<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\ICacheProvider,
	Pi\Interfaces\IContainable,
	Pi\Interfaces\IContainer,
	Pi\Interfaces\ILog,
	Pi\ServiceModel\PostArticleRequest,
	Pi\ServiceModel\ArticleState,
	Pi\Host\HostProvider,
	Pi\Common\Http\HttpMessage,
	Pi\Common\Http\HttpRequest;




/**
 * @description
 * Extract posts informations from Wordpress websites and create refered Articles
 * For now only RSS is implemented, so the body of the post isn't returned
 */
class WordpressCrawler implements IContainable {

	const REDIS_DOMAIN_CACHE_KEY = 'redis::app::';

	const REDIS_DOMAIN_MODIFIED_KEY = 'redis::app-mod::';

	const REDIS_DOMAIN_IDS = 'redis::app-ids::';

	const NAME = 'Pi\ServiceInterface\WordpressCrawler';

	public function __construct(
		protected ICacheProvider $cache,
		protected ILog $logger)
	{
		
	}

	public function ioc(IContainer $ioc) {}


	public static function redisDomainModified(string $domain)
	{
		return self::REDIS_DOMAIN_MODIFIED_KEY . $domain;
	}

	public static function redisDomainIds(string $domain)
	{
		return self::REDIS_DOMAIN_IDS . $domain;
	}

	/**
	 * @description
	 * Returns the id from a link using p query parameter
	 * @example 
	 * http://domain.pt/?p=123
	 */
	public static function extractIdFromGuid(string $guid) : ?string
	{
		$parts = explode('p=', $guid);
		if(count($parts) < 2) {
			return null;
		}
		return $parts[1];
	}

	public function fetch(string $domain, $skip = 0, $take = 50)
	{
		if(!is_string($domain) || empty(trim($domain))) {
			return false;
		}
		
		$pages = 10;
		$uris = array();
		for ($i=0; $i < count($pages) ; $i++) { 
			$page = $i + 1;
			$uris[] = $domain . '/feed/?paged=' . $page;
		}

		$responses = array();
		$pubDate = null;

		foreach($uris as $key => $uri) {
			
			$req = new HttpRequest($uri);
			$res = $req->send();
			$responses[] = $res;

			try {
				$xml = new \SimpleXMLElement($res->getBody());
				
				if($key === 0) {
					$pubDate =  new \DateTime((string)$xml->channel->item[0]->pubDate);	
					if(is_null($pubDate)) {
						$pubDate = new \DateTime('now');
					}
				}
				
				$items = array();
				$chan = $xml->channel;
				
				foreach($chan->item as $item) {

					if(is_null($item) || !is_object($item)) {
						continue;
					}

					$id = self::extractIdFromGuid((string)$item->guid);
					if($this->hasId($domain, $id)) {
						return false;
					}

					if(!property_exists($item, 'title') ||
						!property_exists($item, 'description')) {
						$this->logger->error(sprintf('Item dont have all required properties: %s', json_encode($item)));
						//return false;
					}

					$itemDate =  new \DateTime((string)$item->pubDate);	
					
					$body = $this->formatDescription((string)$item->children('content', true));
					$req = new PostArticleRequest();

					$req->setName($this->formatName((string)$item->title));
					$req->setArticleBody($body);
					$req->setHeadline($this->formatHeadline((string)$item->description));
					$req->setState(ArticleState::Draft);
					$cover = self::extractImageFromBody($body);
					if(!is_null($cover)) {
						//die(print_r($cover));
						$req->setImage($cover);
						$req->setThumbnailUrl($cover);
					}

					$date =  new \DateTime((string)$xml->channel->item[1]->pubDate);
					if(is_null($date)) {
						$date = new \DateTime('now');
					}
					$req->setDatePublished($date);

					$tags = is_array($item->category) ? $item->category : array($item->category);
					$res = HostProvider::execute($req);
					$items[] = $res;
					$this->addId($domain, $id);
					$this->logger->debug(
						sprintf('Inserted the article %s \"%s\"', $res->getArticle()->getId(), $res->getArticle()->getName()));
				}
			}
			catch(\Exception $ex) {
				$this->logger->errorEx($ex);
				throw $ex;
			}
		}
		$this->setCacheUpdated($domain, $pubDate);
	}

	/**
	 * @description
	 * Extracts the image link from a string containing at least one image with the link in source attribute
	 * @example
	 * <p><img src="http://domain.pt/image-png" /></p>
	 */
	public static function extractImageFromBody(string $html) : ?string
	{
		$array = array();
	    preg_match( '/src="([^"]*)"/i', $html, $array ) ;
	    if(!is_array($array) || count($array) === 0) {
	    	return null;
	    }

	    return $array[1];
	}

	protected function normalizeXss(string $value)
	{
		return $value;
	}

	protected function formatName(string $name) : string
	{
		$n = ucwords($name);
		return $this->normalizeXss($n);
	}

	protected function formatDescription(string $desc) : string
	{
		return $this->normalizeXss($desc);
	}

	protected function formatHeadline(string $headline) : string
	{
		$h = ucwords($headline);
		return $this->normalizeXss($h);
	}

	protected function validateFeedResponse($res)
	{
		return !is_null($require);
	}

	protected function isCached(string $domain)
	{
		$last = $this->cache->get(self::redisDomainModified($domain));
		
		return !is_null($last) && is_string($last);
	}

	protected function isNewer(string $domain, \DateTime $lastPubDate)
	{
		$last = $this->cache->get(self::redisDomainModified($domain));
		if(is_null($last) || !is_string($last)) {
			return true;
		}
		$lastDate = new \DateTime($last);
		return $lastDate->getTimestamp() < $lastPubDate->getTimestamp();
	}

	/**
	 * @description
	 * Check if the Id was already crawled
	 * @return true if the article was added
	 */
	protected function hasId(string $domain, string $id)
	{
		return $this->cache->contains(self::redisDomainIds($domain), $id);
	}

	/**
	 * @description
	 * Add the Id do crawled posts list
	 */
	protected function addId(string $domain, string $id)
	{
		$d = new \DateTime('now');
		return $this->cache->add(self::redisDomainIds($domain), $id, $d->format('Y-m-d H:i:s'));
	}

	protected function setCacheUpdated(string $domain, \DateTime $date)
	{
		$this->cache->set(self::redisDomainModified($domain), $date->getTimestamp());
		$this->logger->debug(
			sprintf('Domain %s updated at %s', $domain, $date->format('Y-m-d H:i:s')));
	}

	protected function cacheDomain(string $domain) : void
	{
		$app = array('domain' => $domain);
		$this->cache->set(self::REDIS_DOMAIN_CACHE_KEY . $domain, $app);
	}
}