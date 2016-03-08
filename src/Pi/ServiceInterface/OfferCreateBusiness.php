<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceModel\Types\Offer;
use Pi\ServiceInterface\Data\OfferRepository;
use Pi\Auth\UserRepository;
use Pi\ServiceInterface\Data\ProductRepository;
use Pi\Interfaces\IContainer;
use Pi\Interfaces\IContainable;

class OfferCreateBusiness implements IContainable {


  public function __construct(
    public OfferRepository $offerRepo,
    public UserRepository $userRepo,
    public ProductRepository $productRepo)
  {

  }

  public function ioc(IContainer $ioc) { }

  public function create(\MongoId $productId, Offer $entity)
  {
      $this->offerRepo->insert($entity);
      $this->productRepo->addOffer($productId, $entity);
  }
}
