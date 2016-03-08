<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\ServiceInterface\Data\JobCarrerRepository;
use Pi\ServiceModel\PostCarrearOfferRequest;
use Pi\ServiceModel\PostCarrearOfferResponse;
use Pi\ServiceModel\FindCarrearOfferRequest;
use Pi\ServiceModel\FindCarrearOfferResponse;
use Pi\ServiceModel\GetCarrearOfferRequest;
use Pi\ServiceModel\GetCarrearOfferResponse;
use Pi\ServiceModel\SubscribeJobOfferRequest;
use Pi\ServiceModel\SubscribeJobOfferResponse;
use Pi\ServiceModel\GetJobSubscribersRequest;
use Pi\ServiceModel\GetJobSubscribersResponse;
use Pi\ServiceModel\RemoveJobSubsriptionRequest;
use Pi\ServiceModel\RemoveJobSubsriptionResponse;
use Pi\ServiceModel\SaveJobCareerRequest;
use Pi\ServiceModel\SaveJobCareerResponse;
use Pi\ServiceModel\JobCarrerDto;
use Pi\ServiceModel\Types\JobCarrer;
use Pi\ServiceModel\Types\FeedAction;
use Pi\Common\ClassUtils;

class CarrersService extends Service {

	public JobCarrerRepository $carrerRepo;

	public UserFeedBusiness $feedBus;

	<<Request,Route('/job/career'),Method('POST')>>
	public function postOffer(PostCarrearOfferRequest $request)
	{
		$response = new PostCarrearOfferResponse();
		$entity = new JobCarrer();

		ClassUtils::mapDto($request, $entity);

		$this->carrerRepo->insert($entity);
		$dto = new JobCarrerDto();
		ClassUtils::mapDto($entity, $dto);

		$action = new FeedAction(
			$this->request()->getUserId(),
			new \DateTime('now'),
			false,
			'basic',
			'normal',
			array('id' => (string)$entity->id(), 'title' => $request->getTitle(), 'companyLogo' => $request->getCompanyLogo(), 'title' => $request->getTitle(), 'companyName' => $request->getCompanyName()),
			'career-offer');

		$action->setAuthor($this->request->author());

		$this->feedBus->createPublic($action);
		$response->setCarrer($dto);
		return $response;
	}

	<<Request,Route('/job/career/:id'),Method('POST')>>
	public function saveOffer(SaveJobCareerRequest $request)
	{
		$res = $this->carrerRepo
			->queryBuilder()
			->update()
			->field('_id')->eq($request->id())
			->field('title')->set($request->getTitle())
			->field('excerpt')->set($request->getExcerpt())
			->field('description')->set($request->getDescription())
			->field('companyLogo')->set($request->getCompanyLogo())
			->field('aboutCompany')->set($request->getAboutCompany())
			->field('address')->set($request->getAddress())
			->field('skillsRequirements')->set($request->getSkillsRequirements())
			->field('companyName')->set($request->getCompanyName())
			->getQuery()
			->execute();

		$response = new SaveJobCareerResponse();
		return $response;
	}

	<<Request,Route('/job/career'),Method('GET')>>
	public function findOffers(FindCarrearOfferRequest $request)
	{
		$res = new FindCarrearOfferResponse();

		$data = $this->carrerRepo
			->queryBuilder('Pi\ServiceModel\JobCarrerDto')
			->hydrate(true)
			->limit($request->getLimit())
			->skip($request->getSkip())
			->find()
			->getQuery()
			->execute();

		if(is_array($data))
			$res->setCarrers($data);

		return $res;
	}

	<<Request,Route('/job/career/:id'),Method('GET')>>
	public function getOffer(GetCarrearOfferRequest $request)
	{
		$dto = $this->carrerRepo->getAs($request->getId(), 'Pi\ServiceModel\JobCarrerDto');

		$response = new GetCarrearOfferResponse();
		$response->setCarrer($dto);

		return $response;
	}


	<<Request,Route('/job/career-subscribe'),Method('POST')>>
	public function subscribe(SubscribeJobOfferRequest $request)
	{
		$response = new SubscribeJobOfferResponse();
		$this->carrerRepo
			->queryBuilder()
			->update()
			->field('subscribers')->push($request->getId())
			->getQuery()
			->execute();

		return $response;
	}

	<<Request,Route('/job/career-subscribe'),Method('GET')>>
	public function getSubscribers(GetJobSubscribersRequest $request)
	{
		$response = new GetJobSubscribersResponse();
		$offers = $this->carrerRepo
			->queryBuilder()
			->field('_id')->eq($request->getId())
			->getQuery()
			->getSingleResult();


		return $response;
	}

	<<Request,Route('/job/career-subscribe'),Method('DELETE')>>
	public function removeSubscription(RemoveJobSubsriptionRequest $request)
	{
		$response = new RemoveJobSubsriptionResponse();
		$this->carrerRepo
			->queryBuilder()
			->remove()
			->field('_id')->eq($request->getId())
			->getQuery()
			->execute();
		return $response;
	}
}
