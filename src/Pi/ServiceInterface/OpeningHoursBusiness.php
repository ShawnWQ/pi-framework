<?hh

namespace Pi\ServiceInterface;

use Pi\Service,
	Pi\Interfaces\IOpeningHoursModel,
	Pi\Common\ClassUtils,
	Pi\ServiceModel\Types\OpeningHoursSpecification,
	Pi\Odm\MongoRepository;




class OpeningHoursBusiness {
	
	static $REPOSITORY_FIELDS = array('closes', 'dayOfWeek', 'opens', 'validFrom', 'validThrough');

	public function __construct(
		protected MongoRepository $repository,
		protected string $key = 'openingHours'
	)
	{

	}

	public function add(IOpeningHoursModel $model)
	{
		$dto = new OpeningHoursSpecification();
		$entity = ClassUtils::mapDto($model, $dto);
	    $this->repository
	      ->queryBuilder()
	      ->update()
	      ->field('openingHours')->push($dto)
	      ->getQuery()
	      ->execute();

	}
}