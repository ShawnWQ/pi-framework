<?hh

namespace SpotEvents\ServiceInterface;

use Pi\Service;
use SpotEvents\ServiceModel\PostModalityRequest;
use SpotEvents\ServiceModel\PostModalityResponse;
use SpotEvents\ServiceModel\GetModalityRequest;
use SpotEvents\ServiceModel\GetModalityResponse;
use SpotEvents\ServiceModel\GetModalitiesRequest;
use SpotEvents\ServiceModel\GetModalitiesResponse;
use SpotEvents\ServiceModel\ModalityDto;
use SpotEvents\ServiceModel\Types\Modality;
use SpotEvents\ServiceInterface\Data\ModalityRepository;
use Pi\Common\ClassUtils;


class ModalityService extends Service {

	public ModalityRepository $modalityRepo;

	<<Request,Method('POST'),Route('/modality')>>
	public function post(PostModalityRequest $request)
	{
		$entity = new Modality();
		ClassUtils::mapDto($request, $entity);

		$this->modalityRepo->insert($entity);

		$dto = new ModalityDto();
		ClassUtils::mapDto($entity, $dto);

		$response = new PostModalityResponse();
		$response->setModality($dto);

		return $response;
	}

	<<Request,Method('GET'),Route('/modality/:id')>>
	public function get(GetModalityRequest $request)
	{
		$modality = $this->modalityRepo->getAs($request->getId(), 'SpotEvents\ServiceModel\ModalityDto');
		$response = new GetModalityResponse();
		$response->setModality($modality);
		return $response;
	}

	<<Request,Method('GET'),Route('/modality')>>
	public function find(GetModalitiesRequest $request)
	{
		$data = $this->modalityRepo->queryBuilder('SpotEvents\ServiceModel\ModalityDto')
			->find()
			->hydrate()
			->getQuery()
			->execute();

		$response = new GetModalitiesResponse();
		$response->setModalities($data);

		return $response;
	}
}	