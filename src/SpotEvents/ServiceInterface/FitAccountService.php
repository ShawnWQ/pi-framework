<?hh

namespace Pi\SpotEvents;

use Pi\Service;
use Pi\Auth\UserRepository;
use Pi\SpotEvents\ServiceModel\Types\FitProfiles;
use Pi\SpotEvents\ServiceModel\PostPersonalTrainnerApplication;
use Pi\SpotEvents\ServiceModel\FindPersonalTrainners;
use Pi\SpotEvents\ServiceModel\FindPersonalTrainnersResponse;




class FitAccountService extends Service {

	public UserRepository $userRepo;

	public function findInstitutions()
	{

	}

	public function applyToInstitution()
	{

	}

	public function acceptInstitution()
	{

	}

	public function dennyInstitution()
	{

	}

	public function removeInstitutionProfile()
	{

	}

	<<Request('/personal-trainner'),Method('GET')>>
	public function findPersonalTrainners(FindPersonalTrainners $request)
	{

		$users = $this->userRepository
			->getQueryBuilder()
			->field('profiles')->in(FitProfiles::PersonalTrainner)
			->getQuery()
			->toArray();

		$response = new FindPersonalTrainnersResponse();
		$response->setUsers($users);
		return $response;
	}
	
	public function postOfferPersonalTrainner()
	{

	}

	public function updatePersonalTrainnerRate()
	{

	}

	<<Request('/personal-trainner'),Method('POST')>>
	public function applyToPersonalTrainner(PostPersonalTrainnerApplication $request)
	{
		$this->userRepository
			->getQueryBuilder()
			->update()
			->field('profiles')->push(FitProfiles::PersonalTrainner)
			->field('_id')->eq($this->request()->getUserId())
			->getQuery()
			->execute();
	}

	public function acceptPersonalTrainner()
	{

	}

	public function denyPersonalTrainner()
	{

	}

	public function removePersonalTrainnerProfile()
	{

	}
}