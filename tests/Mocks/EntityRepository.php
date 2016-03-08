<?hh

namespace Mocks;
use Pi\Odm\MongoRepository;

class EntityRepository<T> extends MongoRepository<T> {
	
	public function pushToken(\MongoId $id, string $value)
	{
		return $this->queryBuilder()
			->update()
			->field('_id')->eq($id)
			->field('tokens')->push($value)
			->getQuery()
			->execute();
	}

	public function pushInscriptions(\MongoId $id, MockEntity2 $emb)
	{
		
		return $this->queryBuilder()
			->update()
			->field('_id')->eq($id)
			->field('inscriptions')->push($emb)
			->getQuery()
			->execute();
	}
}