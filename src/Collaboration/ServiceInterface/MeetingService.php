<?hh

namespace Collaboration\ServiceInterface;


use Pi\Service;
use Collaboration\ServiceModel\PublishMeeting;
use Collaboration\ServiceModel\CreateMeeting;
use Collaboration\ServiceInterface\Data\MeetingRepository;
use Pi\Common\ClassUtils;


class MeetingService extends Service {
	
	public MeetingRepository $meetingRepo;

	public function __construct()
	{
		
	}

	<<Request,Route('/meeting'),Method('POST')>>
	public function create(CreateMeeting $request)
	{

	}

	<<Request,Route('/meeting/publish'),Method('POST')>>
	public function publish(PublishMeeting $request)
	{

	}
}