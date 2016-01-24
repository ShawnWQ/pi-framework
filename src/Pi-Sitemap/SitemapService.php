<?hh

namespace Pi\Sitemap;

use Pi\Service;
use Pi\Sitemap\ServiceModel\PublishSitemap;
use Pi\Sitemap\ServiceModel\PublishSitemapResponse;
use Pi\Sitemap\ServiceModel\Sitemap;
use Pi\Common\ClassUtils;


class SitemapService extends Service {
	
	public SitemapRepository $repo;

	public function publish(PublishSitemap $request)
	{
		$response = new PublishSitemapResponse();
		return $response;
	}
}