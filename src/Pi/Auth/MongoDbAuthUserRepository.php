<?hh

namespace Pi\Auth;
use Pi\Odm\MongoRepository;
use Pi\EventManager;
use Pi\Odm\UnitWork;
use Pi\Odm\DocumentManager;

class MongoDbAuthUserRepository extends MongoRepository<TUser> {

	public function getByEmailAndPw(string $email, string $passwordHash)
	{
		return $this->queryBuilder()
			->hydrate()
			->field('email')->eq($email)
			->field('password')->eq($passwordHash)
			->getQuery()
			->getSingleResult();
	}
}
