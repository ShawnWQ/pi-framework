<?hh

namespace SpotEvents\ServiceInterface;
use Pi\ServiceInterface\LikesProvider;
use Pi\ServiceModel\GetEventLikesRequest;
use Pi\ServiceModel\GetEventLikesResponse;
use Pi\ServiceModel\PostEventLikeRequest;
use Pi\ServiceModel\PostEventLikeResponse;

class EventLikesProvider extends LikesProvider {

	public function __construct()
	{
		parent::__construct('events');
	}
}
