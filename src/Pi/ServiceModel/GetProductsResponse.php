<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetProductsResponse extends Response {

  protected ?array $products;

  public function getProducts() : ?array
  {
    return $this->products;
  }

  public function setProducts(array $products) : void
  {
    $this->products = $products;
  }
}
