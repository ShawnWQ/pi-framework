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
	Pi\Auth\Interfaces\ICryptorProvider;




class RegisterService extends Service {

	public UserRepository $userRep;

    public RegistrationAvailabilityService $availableService;

    public ICryptorProvider $cryptor;


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
	public function basicRegistration(BasicRegisterRequest $request)
	{

		$r = new RegistrationAvailabilityRequest();
	    $r->setEmail($request->email());
	    if($this->availableService->verifyEmail($r)->isAvailable() === false) {
	        return HttpResult::createCustomError(AuthServiceError::EmailAlreadyRegistered, gettext(AuthServiceError::EmailAlreadyRegistered));
	    }

		$account = $this->mapBasicRequestToUserEntity($request);

		$this->userRep->insert($account);
		$this->redisClient()->hset('user::' . (string)$account->id(), 'name', $account->getDisplayName());

		$user = new UserDto();
		$event = new NewUserRegisterArgs($user);

		$user->id($account->id());
		$this->eventManager()->dispatch('Pi\ServiceInterface\Events\NewUserRegisterArgs', $event);
		$response = new BasicRegisterResponse();
		$response->setId($account->id());

		return $response;
	}

	<<Request,Route('/account/confirm'),Method('POST')>>
	public function confirmEmail(ConfirmEmailRequest $request)
	{

	}

	protected function mapBasicRequestToUserEntity(BasicRegisterRequest $dto)
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
		$entity->setUsername($dto->email());
		$entity->displayName($dto->displayName());
		$entity->setCulture('pt-pt');
		$entity->setCountry('Portugal');

		return $entity;
	}
}
