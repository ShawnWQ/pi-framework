<?hh

namespace Pi\ServiceInterface;

use Pi\Interfaces\IAppSettings,
	Pi\ServiceModel\ContentPublish;




class FacebookContentPublisher {

	const pageFeedUrl = 'https://graph.facebook.com/%s/feed';

	public function __construct(
		protected IAppSettings $settings)
	{
		
	}

	public function publish(ContentPublish $obj)
	{
		$url = sprintf(self::page, $pageId);
	}
}