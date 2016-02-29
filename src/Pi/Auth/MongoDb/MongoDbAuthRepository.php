<?hh

namespace Pi\Auth\MongoDb;

use Pi\Odm\MongoRepository,
    Pi\Auth\UserAuth,
    Pi\Auth\AuthExtensions,
    Pi\Auth\UserAuthDetails,
    Pi\Auth\Interfaces\IUserAuthRepository,
    Pi\Auth\Interfaces\IAuthRepository,
    Pi\Auth\Interfaces\IUserAuth,
    Pi\Auth\Interfaces\IAuthSession,
    Pi\Auth\Interfaces\IAuthTokens,
    Pi\Auth\Interfaces\IUserAuthDetails,
    Pi\Auth\Interfaces\IAuthDetailsRepository,
    Pi\Redis\Interfaces\IRedisClient;

class MongoDbAuthRepository extends MongoRepository<TAuth> implements IUserAuthRepository, IAuthRepository {

  public IAuthDetailsRepository $authDetails;

  public function tryAuthenticate(string $userNameOrEmail, string $password) : ?IUserAuth
  {
    $user = $this->getUserAuthByUserName($userNameOrEmail);
    if(is_null($user)) {
      return null;
    }
    if(is_null($user->getPasswordHash())) throw new \Exception(print_r($user));

    if($user->getPasswordHash()!== $password) {
      die('nulo');
      return null;
    }

    return $user;
  }

  public function createUserAuth(IUserAuth $newUser, string $passwordHash) : IUserAuth 
  {
    $newUser->setPasswordHash($passwordHash);
    $newUser->setCreatedDate(new \DateTime('now'));
    $newUser->setModifiedDate($newUser->getCreatedDate());

    $this->insert($newUser);
    return $newUser;
  }

  public function createAuth(?IAuthSession $session) : IUserAuth 
  {
    $newUser = new UserAuth();
    if($session == null)
      $session = new AuthUserSession();
    AuthExtensions::populateUserAuthWithSession($newUser, $session);
    $this->insert($newUser);

    return $newUser;
  }

  public function createOrMergeAuthSession(IAuthSession $session, IAuthTokens $tokens) : IUserAuthDetails 
  {
    $registered = true;

    $userAuth = $this->getUserAuth($session, $tokens);
    
    if($userAuth == null) {
      $registered = false;
      $userAuth = $this->createAuth($session);
      $tokens->setUserId($userAuth->getId());
      $tokens->setUserAuthId($userAuth->getId());
    }

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
      $this->authDetails->insert($authDetails);
      
    }

    $userAuth->setModifiedDate(new \DateTime('now'));


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


  public function getUserAuth(IAuthSession $session, IAuthTokens $tokens) : ?IUserAuth
  {
    if(!is_null($session->getUserId())) {
      $userAuth = $this->getUserAuthById($session->getUserId());
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

    $userAuth = $this->getUserAuthById($provider->getUserId());
    return $userAuth;
  }

  public function getUserAuthById(\MongoId $id)
  {
    return $this->get($id);
    
  }

  public function getUserAuthByUserName(string $userNameOrEmail) : ?IUserAuth
  {
    return $this->queryBuilder()
      ->find()
      ->hydrate()
      ->field('email')->eq($userNameOrEmail)
      ->getQuery()
      ->getSingleResult();
  }


  public function getUserAuthDetails($userAuthId) : array
  {
    return $this->queryBuilder('Pi\Auth\UserAuthDetails')
      ->find()
      ->field('userId')->eq($userAuthId)
      ->getQuery()
      ->getSingleResult() ?: array();
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
    $tokens = $this->getUserAuthDetails($session->getUserId());
    AuthExtensions::populateSessionWithUserAuth($session, $userAuth, $tokens);
  }
}
