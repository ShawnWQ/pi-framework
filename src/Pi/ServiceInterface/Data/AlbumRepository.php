<?hh

namespace Pi\ServiceInterface\Data;

use Pi\Odm\MongoRepository;
use Pi\ServiceModel\AlbumType;

class AlbumRepository extends MongoRepository<TAlbum> {

  public function getDto(\MongoId $id)
  {
    return $this->getAs($id, 'Pi\ServiceModel\AlbumDto');
  }

  public function getProfileAlbumId(\MongoId $userId)
  {
    $album = $this->queryBuilder()
      ->hydrate(false)
      ->field('authorId')->eq($userId)
      ->field('type')->eq(AlbumType::Profile)
      ->select('_id')
      ->getQuery()
      ->toArray();
    return is_null($album) || !array_key_exists('_id', $album[0]) ? null : $album[0]['_id'];
  }
}
