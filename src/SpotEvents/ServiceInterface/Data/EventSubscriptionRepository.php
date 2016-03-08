<?hh

namespace SpotEvents\ServiceInterface\Data;

use Pi\Odm\MongoRepository;

class EventSubscriptionRepository extends MongoRepository<TEventSubs> {

	/**
	 * @return SpotEvents\ServiceModel\Types\EventSubscription
	 */
	public function getByEntityId(\MongoId $id, $hydrate = true, ?array $fields = null)
	{
		$query = $this->queryBuilder()
			->field('entityId')->eq($id)
			->hydrate($hydrate);

		if(is_array($fields)) {
			call_user_func(array($query, 'select'), $fields);
		}

		return $query
			->getQuery()
			->getSingleResult();
	}
}