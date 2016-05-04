<?hh

namespace Pi\ServiceInterface\Data;

use Pi\Odm\MongoRepository;

class UserRepository extends MongoRepository<TUser> {

  public function getDto(\MongoId $id)
  {
    return $this->getAs($id, 'Pi\ServiceModel\UserDto');
  }
}
