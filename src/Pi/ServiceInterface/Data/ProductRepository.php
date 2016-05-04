<?hh

namespace Pi\ServiceInterface\Data;

use Pi\Odm\MongoRepository;
use Pi\ServiceModel\Types\Offer;

class ProductRepository extends MongoRepository<TProduct> {

  public function addOffer(\MongoId $productId, Offer $entity)
  {

      $this->queryBuilder()
        ->update()
        ->field('_id')->eq($productId)
        ->field('offers')->push($entity)
        ->getQuery()
        ->execute();
  }  
}
