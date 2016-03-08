<?hh


namespace Pi\ServiceInterface;

use Pi\ServiceModel\FindUser;
use Pi\ServiceModel\FindUserResponse;
use Pi\Auth\UserRepository;
use Pi\Service;

class FindUserService extends Service {

    public UserRepository $userRepo;

    <<Request,Route('/api/user')>>
    public function normal(FindUser $request)
    {
      $response = new FindUserResponse();
      $users = $this->userRepo
        ->queryBuilder('Pi\ServiceModel\UserDto')
        ->find()
        ->hydrate()
        ->getQuery()
        ->execute();
        $response->setUsers($users);
      return $response;
    }
}
