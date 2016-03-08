<?hh

namespace Pi\Auth;

use Pi\Service;
use Pi\ServiceModel\BasicRegisterRequest;
use Pi\ServiceModel\BasicRegisterResponse;
use Pi\ServiceModel\BasicAuthenticateRequest;
use Pi\ServiceModel\BasicAuthenticateResponse;
use Pi\ServiceModel\ConfirmEmailRequest;
use Pi\ServiceModel\GetUserRequest;
use Pi\ServiceModel\GetUserResponse;
use Pi\ServiceModel\UserDto;
use Pi\ServiceModel\RegistrationAvailabilityRequest;
use Pi\ResponseUtils;
use Pi\HttpResult;
use Pi\ServiceInterface\Events\NewUserRegisterArgs,
	Pi\Auth\Interfaces\ICryptorProvider,
	Pi\ServiceInterface\AbstractMailProvider,
	Pi\Common\RandomString;




class RegisterService extends Service {

	public UserRepository $userRep;

    public RegistrationAvailabilityService $availableService;

    public ICryptorProvider $cryptor;

    public AbstractMailProvider $mailProvider;

    const REDIS_CONFIRM_TOKEN = 'mailtoken::';

    const INVALID_EMAIL_TOKEN = 'invalid-email-token';


	<<Request,Route('/user/:id'),Method('GET')>>
	public function getUser(GetUserRequest $request)
	{
		$user = $this->userRep
			->getAs($request->getId(), 'Pi\ServiceModel\UserDto');
		$response = new GetUserResponse();
		if(!is_null($user)) {
			$response->setUser($user);
		}
		return $response;
	}

	<<Request,Route('/register'),Method('POST')>>
	public function register(BasicRegisterRequest $request)
	{
		$r = new RegistrationAvailabilityRequest();
	    $r->setEmail($request->email());
	    if($this->availableService->verifyEmail($r)->isAvailable() === false) {
	        return HttpResult::createCustomError(AuthServiceError::EmailAlreadyRegistered, _(AuthServiceError::EmailAlreadyRegistered));
	    }

		$account = $this->mapBasicRequestToUserEntity($request);

		$this->userRep->insert($account);
		$this->redisClient()->hset('user::' . (string)$account->id(), 'name', $account->getDisplayName());

		$user = new UserDto();
		$this->mapUserEntityToUserDto($account, $user);
		$event = new NewUserRegisterArgs($user);
		
		$this->eventManager()->dispatch('Pi\ServiceInterface\Events\NewUserRegisterArgs', $event);
		$response = new BasicRegisterResponse();
		$response->setId($account->id());

		return $response;
	}

	public static function mapUserEntityToUserDto(UserEntity $entity, UserDto $dto)
	{
		$dto->id($entity->id());
		$dto->setDisplayName($entity->displayName());
		$dto->setEmail($entity->getEmail());
	}

	<<Subscriber('Pi\ServiceInterface\Events\NewUserRegisterArgs')>>
	public function onNewUserRegistered(CreateAccountConfirmationToken $request)
	{
		$token = RandomString::generate();
		$email = (string)$request->getUser()->getEmail();
		$id = (string)$request->getUser()->id();
		$displayName = $request->getUser()->getDisplayName();
		$redisKey = self::REDIS_CONFIRM_TOKEN . $id;

		$this->cache()->set($redisKey, $token);
		$this->cache()->expire($redisKey, 3600);
		$body = $this->getMailConfirmationBody($token, $displayName, $email, $id);
		$this->mailProvider->send($displayName, $email, _('Account Confirmation'), $body);	
	}

	protected function getMailConfirmationBody(string $token, string $displayName, $email, $id)
	{
		$link = $this->appConfig()->absoluteUrl() . '/account/confirm?token=' . $token . '&id=' . $id;
		$code = sprintf(_('You have to confirm your email.<br>Follow this link: <a href="%s">%s</a>'), $link, $link);
		return $code;
	}

	<<Request,Route('/account/confirm'),Method('GET')>>
	public function confirmEmail(ConfirmEmailRequest $request)
	{
		if($this->cache()->get(self::REDIS_CONFIRM_TOKEN . (string)$request->getId()) != $request->getToken()) {
			return HttpResult::createCustomError(self::INVALID_EMAIL_TOKEN, _(self::INVALID_EMAIL_TOKEN));
		}

		$r = $this->userRep->queryBuilder()
			->update()
			->field('_id')->eq($request->getId())
			->field('state')->set(3)
			->getQuery()
			->execute();

		return $r;
	}

	public function mapBasicRequestToUserEntity(BasicRegisterRequest $dto)
	{
		$entity = new UserEntity();
		if(\MongoId::isValid($dto->id())) {
			$entity->id($dto->id());
		}
        $entity->state(AccountState::EmailNotConfirmed);
		$entity->firstName($dto->firstName());
		$entity->lastName($dto->lastName());
		$entity->email($dto->email());
		$hash = $this->cryptor->encrypt($dto->password());
		//$entity->password($hash);
		$entity->setPasswordHash($hash);
		$entity->setUsername($dto->getUsername() ?: $dto->email());
		$entity->displayName($dto->displayName() ?: $dto->firstName().' '.$dto->lastName());
		$entity->setCulture('pt-pt');
		$entity->setCountry('Portugal');

		return $entity;
	}
}
