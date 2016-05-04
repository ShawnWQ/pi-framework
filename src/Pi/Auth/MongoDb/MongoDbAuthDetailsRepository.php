<?hh

namespace Pi\Auth\MongoDb;

use Pi\Odm\MongoRepository;
use Pi\Auth\UserAuth;
use Pi\Auth\Interfaces\IUserAuthRepository;
use Pi\Auth\Interfaces\IAuthRepository;
use Pi\Auth\Interfaces\IUserAuth;
use Pi\Auth\Interfaces\IAuthSession;
use Pi\Auth\Interfaces\IAuthTokens,
	Pi\Auth\Interfaces\IAuthDetailsRepository,
	Pi\Auth\AuthToken,
	Pi\Auth\UserAuthDetails,
	Pi\Auth\AuthRedisKeys,
	Pi\Common\RandomString;
use Pi\Redis\Interfaces\IRedisClient;


class MongoDbAuthDetailsRepository  extends MongoRepository<TAuth>   {

	public function create($clientId, $scope, string $provider) : UserAuthDetails
	{
	  	$entity = new UserAuthDetails();
	  	$entity->setProvider($provider);
	  	$entity->setAccessToken(RandomString::generate(20));
	    $entity->setUserId($clientId);
	    
	    return $entity;
	}

	public function getTokenByUserId($userAuthId) : ?string
	{
		$auth = $this->queryBuilder('Pi\Auth\UserAuthDetails')
	  		->find()
	  		->hydrate(false)
	 	 	->field('userId')->eq($userAuthId)
	  		->getQuery()
	  		->getSingleResult();

	  	return is_array($auth) ? $auth['accessToken'] : null;
	}

	public function getUserIdByToken($token, $provider) : ?\MongoId
	{
		$auth = $this->queryBuilder('Pi\Auth\UserAuthDetails')
	  		->find()
	  		->hydrate(false)
	 	 	->field('accessToken')->eq($token)
	 	 	->field('provider')->eq($provider)
	  		->getQuery()
	  		->getSingleResult();

	  	return is_array($auth) ? $auth['userId'] : null;
	}

	public function getByUserId($userAuthId) : ?UserAuthDetails
	{
		return $this->queryBuilder('Pi\Auth\UserAuthDetails')
	  		->find()
	  		->hydrate(true)
	 	 	->field('userId')->eq($userAuthId)
	  		->getQuery()
	  		->getSingleResult();
	}

	public function getAuthByToken(string $token) : ?UserAuthDetails
	{
		return $this->queryBuilder('Pi\Auth\UserAuthDetails')
	  		->find()
	  		->hydrate(true)
	 	 	->field('token')->eq($token)
	  		->getQuery()
	  		->getSingleResult() ?: null;
	}

	

	public function getByProvider($userId, $provider)
	{
		return $this->queryBuilder('Pi\Auth\UserAuthDetails')
			->find()
			->field('userId')->eq($userId)
			->field('provider')->eq($provider)
			->getQuery()
			->getSingleResult();
	}
}