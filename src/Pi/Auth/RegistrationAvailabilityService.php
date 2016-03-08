<?hh

namespace Pi\Auth;
use Pi\Service;
use Pi\ServiceModel\RegistrationAvailabilityResponse;
use Pi\ServiceModel\RegistrationAvailabilityRequest;

class RegistrationAvailabilityService extends Service {

	public UserRepository $userRepository;

	public function verifyEmail(RegistrationAvailabilityRequest $request)
	{
        $counter = $this->userRepository->queryBuilder()
            ->field('email')->eq($request->getEmail())
            ->getQuery()
            ->count();

        $response = new RegistrationAvailabilityResponse();
        $response->setAvailable($counter === 0);
        return $response;
	}

}