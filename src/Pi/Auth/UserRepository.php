<?hh

namespace Pi\Auth;
use Pi\Odm\MongoRepository;

class UserRepository extends MongoRepository<TUser> {

	public function verifyEmailInUse(string $email)
	{
		return $this->queryBuilder()
            ->field('email')->eq($email)
            ->getQuery()
            ->count() > 0;
	}

	public function getByEmail(string $email)
	{
		return $this->queryBuilder()
			->hydrate()
			->field('email')->eq($email)
			->getQuery()
			->getSingleResult();
	}

	
    public function getDto(\MongoId $id)
	{
		return $this->getAs($id, 'Pi\ServiceModel\UserDto');
	}
}