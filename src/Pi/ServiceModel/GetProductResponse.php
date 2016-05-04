<?hh

namespace Pi\ServiceModel;

use Pi\Response;


class GetProductResponse extends Response {

  protected ProductDto $product;

  public function getProduct() : ProductDto
  {
    return $this->product;
  }

  public function setProduct(ProductDto $dto) : void
  {
    $this->product = $dto;
  }
}
