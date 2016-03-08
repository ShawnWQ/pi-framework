<?hh

namespace Pi\ServiceInterface;
use Pi\Service;
use Pi\ServiceModel\ApplicationChangeDomain;
use Pi\ServiceModel\ApplicationChangeDomainResponse;
use Pi\ServiceModel\ApplicationGetRequest;
use Pi\ServiceModel\ApplicationGetResponse;
use Pi\ServiceModel\ApplicationFindResponse;
use Pi\ServiceModel\ApplicationFindRequest;
use Pi\ServiceModel\ApplicationCreateRequest;
use Pi\ServiceModel\ApplicationCreateResponse;
use Pi\ServiceModel\PostApplicationMessage;
use Pi\ServiceModel\PostApplicationMessageResponse;
use Pi\ServiceModel\UpdateApplicationMailRequest;
use Pi\ServiceModel\UpdateApplicationMailResponse;
use Pi\ServiceModel\GetApplicationMailRequest;
use Pi\ServiceModel\GetApplicationMailResponse;
use Pi\ServiceModel\ApplicationDto;
use Pi\ServiceModel\ApplicationMailDto;
use Pi\ServiceModel\AppMessageDto;
use Pi\ServiceModel\Types\AppMessage;
use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\Types\Application;
use Pi\ServiceInterface\Data\ApplicationRepository;
use Pi\ServiceInterface\Data\AppMessageRepository;
use Pi\Common\ClassUtils;

class ApplicationService extends Service {


	public AppMessageRepository $messageRepo;
	public ApplicationRepository $appRepo;
	public AbstractMailProvider $mailProvider;

/*	<<Request,Route('/asdnfxvc213123nedzkjcxnvkxcvcxv'),Method('GET')>>
	public function defaultAccount(BasicRegisterRequest $req)
	{
		$appId = $this->request()->appId() ?: new \MongoId();
		if(!$this->cache()->get($this->getCacheAppBootKey($appId)) !== true) {
			$req = new BasicRegisterRequest();
			$req->setFirstName('Guilherme');
			$req->setLastName('Cardoso');
			$req->setEmail('email@guilhermecardoso.pt');
			$req->setPassword('123123');
			$res = $this->execute($req);

		}
	}
*/
	protected function getCacheAppBootKey(\MongoId $appId) : string
	{
		return sprintf('app::%s::boot', (string)$appId);
	}

	<<Request,Route('/app-message'),Method('POST')>>
	public function postMessage(PostApplicationMessage $request)
	{
		$entity = new AppMessage();
		ClassUtils::mapDto($request, $entity);
		$this->messageRepo->insert($entity);

		$dto = new AppMessageDto();
		ClassUtils::mapDto($entity, $dto);

		$response = new PostApplicationMessageResponse();
		$resposne->setMessage($dto);
	}

	public function getMessages()
	{
		//$cached = $this->cache()->get($this->appConfig())
	}

	<<Request, Auth,Route('/application'),Method('POST')>>
	public function create(ApplicationCreateRequest $request)
	{
		
		$dto = new ApplicationDto();
		$entity = new Application();
		ClassUtils::mapDto($request, $entity);
		$this->appRepo->insert($entity);

		// save default mail settings
		$this->mailProvider->setDefault();

		$this->appRepo->setRedisDomain((string)$entity->getId(), $request->getDomain());
		$response = new ApplicationCreateResponse($dto);
		return $response;
	}
	<<Request,Method('POST'),Route('/application/domain'),Auth>>
	public function changeDomain(ApplicationChangeDomain $request)
	{
		$this->appRepo->setRedisDomain((string)$request->getAppId(), $request->getNewDomain());

		$this->appRepo->queryBuilder()
			->update()
			->field('_id')->eq($request->getAppId())
			->field('domain')->set($request->getNewDomain())
			->getQuery()
			->execute();

		$response = new ApplicationChangeDomainResponse();
		return $response;
	}

	<<Request, Method('GET'),Route('/application'),Auth>>
	public function find(ApplicationFindRequest $request)
	{
		$response = new ApplicationFindResponse();

		$results = $this->appRepo->queryBuilder('Pi\ServiceModel\ApplicationDto')
			->find()
			->limit($request->getLimit())
			->skip($request->getSkip())
			->hydrate()
			->getQuery()
			->execute();

		$response->setApplications($results);

		return $response;
	}

	<<Request,Method('GET'),Route('/application/:appId'),Auth>>
	public function get(ApplicationGetRequest $request)
	{
		$entity = $this->appRepo->get($request->getAppId());
		$dto = new ApplicationDto();
		ClassUtils::mapDto($entity, $dto);

		$response = new ApplicationGetResponse();
		$response->setApplication($dto);
		return $response;
	}

	<<Request,Method('GET'),Route('/application-mail')>>
	public function getMail(GetApplicationMailRequest $request)
	{

		$response = new GetApplicationMailResponse();
		$dto = new ApplicationMailDto();
		$dto->setAppId($request->getId());
		$dto->setHeader($this->mailProvider->getBodyHeader());
		$dto->setFooter($this->mailProvider->getBodyFooter());
		$dto->setPort($this->mailProvider->getPort());
		$dto->setHost($this->mailProvider->getHost());
		$response->setMail($dto);
		return $response;
	}	

	<<Request,Method('POST'),Route('/application-mail')>>
	public function saveMail(UpdateApplicationMailRequest $request)
	{
		$response = new UpdateApplicationMailResponse();
		return $response;
	}
}
/*

class ApplicationDto {
	public function __construct(
		protected string $name,
		protected string $description,
		protected ?\MongoId $ownerId = null)
	{

	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function getOwnerId() ?\MongoId
	{
		return $this->ownerId;
	}
}


<<Entity>>
class ApplicationEntity {
	public function __construct(
		protected string $name,
		protected string $description,
		protected ?\MongoId $ownerId = null)
	{

	}

	public function getName() : string
	{
		return $this->name;
	}

	public function setName(string $name) : void
	{
		$this->name = $name;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function setDescription(string $description) : void
	{
		$this->description = $description;
	}

	public function getOwnerId() ?\MongoId
	{
		return $this->ownerId;
	}

	public function setOwnerId(\MongoId $ownerId) : void
	{
		$this->ownerId = $ownerId;
	}
}
class ApplicationRepository {

}
class ApplicationCreateResponse {
	protected ApplicationDto $application ;
}
class ApplicationService extends Service {

	public function __construct(
		protected IRedisClientsManager $redisManager,
		protected ApplicationRepository $repository
		)
	{

	}

	public function get()
	{

	}

	public function create(ApplicationCreate $request)
	{
		$response = new ApplicationCreateResponse();
		return $response;
	}
}*/
