<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/18/15
 * Time: 5:31 AM
 */

namespace SpotEvents\ServiceInterface;

use Pi\Common\ClassUtils;
use Pi\Service;
use Pi\ServiceInterface\UserFeedBusiness;
use Pi\ServiceModel\Types\FeedAction;
use SpotEvents\ServiceModel\CreateGymCampaign;
use SpotEvents\ServiceModel\CreateGymCampaignResponse;
use SpotEvents\ServiceInterface\Data\GymCampaignRepository;
use SpotEvents\ServiceModel\FindGymCampaign;
use SpotEvents\ServiceModel\FindGymCampaignResponse;
use SpotEvents\ServiceModel\GetGymCampaignRequest;
use SpotEvents\ServiceModel\GetGymCampaignResponse;
use SpotEvents\ServiceModel\Types\GymCampaign;

class GymCampaignService extends Service{

    public GymCampaignRepository $campaignRepo;

    public UserFeedBusiness $feedBus;

    <<Request,Auth,Route('/gym-campaign'),Method('POST')>>
    public function post(CreateGymCampaign $request)
    {
        $response = new CreateGymCampaignResponse();
        $entity = new GymCampaign();
        ClassUtils::mapDto($request, $entity);

        $this->campaignRepo->insert($entity);

        $action = new FeedAction(
    			$this->request()->getUserId(),
    			new \DateTime('now'),
    			false,
    			'basic',
    			'normal',
    			array('id' => (string)$entity->id(), 'title' => $request->getTitle(), 'startDate' => $request->getStartDate(), 'endDate' => $request->getEndDate(), 'amount' => $request->getAmount()),
    			'gym-campaign');

    		$action->setAuthor($this->request->author());
    		$this->feedBus->createPublic($action);

        return $response;
    }

    <<Request,Auth,Route('/gym-campaign'),Method('GET')>>
    public function find(FindGymCampaign $request)
    {
        $result = $this->campaignRepo->queryBuilder('SpotEvents\ServiceModel\Types\GymCampaignDto')
            ->find()
            ->hydrate()
            ->getQuery()
            ->execute();

        $response = new FindGymCampaignResponse();
        $response->setCampaigns($result);
        return $response;
    }

    <<Request,Auth,Route('/gym-campaign/:id'),Method('GET')>>
    public function get(GetGymCampaignRequest $request)
    {
        $result = $this->campaignRepo->queryBuilder('SpotEvents\ServiceModel\Types\GymCampaignDto')
            ->find()
            ->field('_id')->eq($request->getId())
            ->hydrate()
            ->getQuery()
            ->getSingleResult();

        $response = new GetGymCampaignResponse();
        $response->setCampaign($result);
        return $response;
    }
}
