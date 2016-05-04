<?hh
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:38 AM
 */

namespace SpotEvents\ServiceInterface\Data;

use Pi\Odm\MongoRepository;
use SpotEvents\ServiceModel\Types\PaymentStatus;

class PaymentRepository extends MongoRepository{

	public function getByReference(string $reference, $hydrate = true, ?array $fields = null)
	{
		$query = $this->queryBuilder()
			->field('reference')->eq($reference)
			->hydrate($hydrate);

		if(is_array($fields)) {
			call_user_func(array($query, 'select'), $fields);
		}

		return $query
			->getQuery()
			->getSingleResult();
	}

	public function updateState(string $reference, PaymentStatus $status)
	{
		$query = $this->queryBuilder()
			->field('reference')->eq($reference)
			->field('status')->set($status)
			->getQuery()
			->execute();
	}
}