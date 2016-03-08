<?hh

namespace Pi\ServiceInterface\Data;

use Pi\Odm\MongoRepository;

class AlbumImageRepository extends MongoRepository<TAlbumImage> {

  public function getDto(\MongoId $id)
  {
    return $this->getAs($id, 'Pi\ServiceModel\AlbumImageDto');
  }
}
