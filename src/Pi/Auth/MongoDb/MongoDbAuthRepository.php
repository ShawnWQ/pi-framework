<?hh

namespace Pi\Auth\MongoDb;

use Pi\Odm\MongoRepository;
use Pi\Auth\UserAuth;
use Pi\Auth\Interfaces\IUserAuthRepository;
use Pi\Auth\Interfaces\IAuthRepository;
use Pi\Auth\Interfaces\IUserAuth;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens;
use Pi\Redis\Interfaces\IRedisClient;

class MongoDbAuthRepository extends MongoRepository<TAuth> implements IUserAuthRepository, IAuthRepository {

  public function tryAuthenticate(string $userNameOrEmail, string $password) : IUserAuth
  {
    $user = $this->getUserAuthByUserName($userNameOrEmail);

    if(is_null($user)) {
      return false;
    }
    $hash = password_hash($password);

    if(!password_verify($user->getPasswordHash(), $hash)) {
      return;
    }

    return $user;
  }

  public function createUserAuth(IUserAuth $newUser, string $password) : IUserAuth
  {
    $newUser = new UserAuth();
    $hash = passord_hash($password);
    $newUser->setPasswordHash($hash);
    $newUser->setCreatedDate(new \DateTime('now'));
    $newUser->setModifiedDate($newUser->getCreatedDate());

    $this->insert($newUser);

    return $newUser;
  }

  public function createOrMergeAuthSession(IAuthSession $session, IAuthTokens $tokens) : IUserAuthDetails
  {
    $userAuth = $this->getUserAuth($session, $tokens) ? : new UserAuth();

    $authDetails = $this->queryBuilder('Pi\Auth\UserAuthDetails')
      ->find()
      ->field('userId')->eq($tokens->getUserId())
      ->field('provider')->eq($tokens->getProvider())
      ->getQuery()
      ->getSingleResult();

    if(is_null($authDetails)) {
      $authDetails = new UserAuthDetails();
      $authDetails->setProvider($tokens->getProvider());
      $authDetails->setUserId($tokens->getUserId());
    }

    // populate missing $authDetails  $tokens
//    $this->saveUserAuth($session)

  // save auth details

    return $authDetails;
  }


  public function saveUserAuth(IAuthSession $session) : void
  {

  }

  public function updateUserAuth(IUserAuth $existing, IUserAuth $newUser, string $password) : IUserAuth
  {

  }


  public function getUserAuth(IAuthSession $session, IAuthTokens $tokens) : IUserAuth
  {
    if(!is_null($session->getUserAuthId())) {
      $userAuth = $this->getUserAuthById($session->getUserAuthId());
      if(!is_null($userAuth)) return $userAuth;
    }

    if(!empty($session->getUserAuthName())) {
      $userAuth = $this->getUserAuthByUserName($session->getUserAuthName());
      if(!is_null($userAuth)) return $userAuth;
    }

    if(is_null($tokens) || empty($tokens->getProvider()) || empty($tokens->getUserId()))
      return null;

    $provider = $this->queryBuilder('Pi\Auth\UserAuthDetails')
      ->find()
      ->field('userId')->eq($tokens->getUserId())
      ->field('provider')->eq($tokens->getProvider())
      ->getQuery()
      ->getSingleResult();

    if(is_null($provider)) {
      return null;
    }

    $userAuth = $this->getUserAuthById($provider->getUserAuthId());
    return $userAuth;

  }

  public function getUserAuthById(\MongoId $id)
  {
      return $this->get($id);
  }

  public function getUserAuthByUserName(string $userNameOrEmail) : IUserAuth
  {
    return $this->queryBuilder()
			->field('email')->eq($email)
			->getQuery()
			->getSingleResult();
  }


  public function getUserAuthDetails($userAuthId) : array
  {
    return $this->queryBuilder('Pi\Auth\UserAuthDetails')
      ->find()
      ->field('userId')->eq($userAuthId)
      ->getQuery()
      ->getSingleResult();
  }

  public function loadUserAuth(IAuthSession $session, IAuthTokens $tokens) : void
  {
    $userAuth = $this->getUserAuth($session, $tokens);
    $this->doLoadUserAuth($session, $userAuth);
  }

  /**
   * Populate session with user auth and get auth details
   */
  public function doLoadUserAuth(IAuthSession $session, IUserAuth $userAuth)
  {
    if(is_null($userAuth)) {
      return;
    }
    //$session->setUserId($userAuth->getId());
    $tokens = $this->getUserAuthDetails($session->getUserId());
    $session->setProviderOAuthAccess($tokens);
  }
}
