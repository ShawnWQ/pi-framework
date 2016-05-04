<?hh

namespace Pi\ServiceModel;


class PostProductRequest  {

	/**
	 * An alias for the item.
	 */
	protected string $alternateName;

	protected string $description;

	protected $image;

	protected string $name;

	protected string $url;

  /**
	 * The brand(s) associated with a product or service, or the brand(s) maintained by an organization or business person.
	 */
	protected $brand;

	/**
	 * A category for the item. Greater signs or slashes can be used to informally indicate a category hierarchy.
	 */
	protected $category;

	/**
	 * The color of the product.
	 */
	protected string $color;

	/**
	 * The depth of the item.
	 */
	protected $depth;

	/**
	 * The GTIN-12 code of the product, or the product to which the offer refers. The GTIN-12 is the 12-digit GS1 Identification Key composed of a U.P.C. Company Prefix, Item Reference, and Check Digit used to identify trade items. See GS1 GTIN Summary for more details.
	 */
	protected $gtin12;

	/**
	 * The GTIN-13 code of the product, or the product to which the offer refers. This is equivalent to 13-digit ISBN codes and EAN UCC-13. Former 12-digit UPC codes can be converted into a GTIN-13 code by simply adding a preceeding zero. See GS1 GTIN Summary for more details.
	 */
	protected string $gtin13;

	/**
	 * The GTIN-14 code of the product, or the product to which the offer refers. See GS1 GTIN Summary for more details.
	 */
	protected string $gtin14;

	/**
	 * The GTIN-8 code of the product, or the product to which the offer refers. This code is also known as EAN/UCC-8 or 8-digit EAN. See GS1 GTIN Summary for more details.
	 */
	protected string $gtin8;

	/**
	 * The height of the item.
	 */
	protected string $height;

	/**
	 * A pointer to another product (or multiple products) for which this product is a consumable.
	 */
	protected array $isConsumableFor;

	/**
	 * A pointer to another, somehow related product (or multiple products).
	 */
	protected array $isRelatedTo;

	/**
	 * A pointer to another, functionally similar product (or multiple products).
	 */
	protected array $isSimilarTo;

	/**
	 * A predefined value from OfferItemCondition or a textual description of the condition of the product or service, or the products or services included in the offer.
	 */
	protected string $itemCondition;

	/**
	 * The model of the product. Use with the URL of a ProductModel or a textual representation of the model identifier. The URL of the ProductModel can be from an external source. It is recommended to additionally provide strong product identifiers via the gtin8/gtin13/gtin14 and mpn properties.
	 */
	protected string $model;

	/**
	 * The Manufacturer Part Number (MPN) of the product, or the product to which the offer refers.
	 */
	protected string $mpn;

	/**
	 * The date of production of the item, e.g. vehicle.
	 */
	protected ?\DateTime $produtionDate;

	/**
	 * The date the item e.g. vehicle was purchased by the current owner.
	 */
	protected ?\DateTime $purchaseDate;

	/**
	 * The release date of a product or product model. This can be used to distinguish the exact variant of a product.
	 */
	protected ?\DateTime $releaseDate;

	/**
	 * The Stock Keeping Unit (SKU), i.e. a merchant-specific identifier for a product or service, or the product to which the offer refers.
	 */
	protected string $sku;

	/**
	 * The weight of the product or person.
	 */
	protected string $weight;

	/**
	 * The width of the item.
	 */
	protected string $width;

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}

  <<String>>
  public function getAlternateName()
  {
    return $this->alternateName;
  }

  public function setAlternateName(string $name)
  {
    $this->alternateName = $name;
  }

  <<String>>
  public function getDescription()
  {
    return $this->description;
  }

  public function setDescription(string $value)
  {
    $this->description = $value;
  }

  /**
   * @return mixed
   */
   <<String>>
  public function getImage()
  {
      return $this->image;
  }

  /**
   * @param mixed $image
   */
  public function setImage($image)
  {
      $this->image = $image;
  }

  <<String>>
  public function getName()
  {
    return $this->name;
  }

  public function setName(string $value)
  {
    $this->name = $value;
  }

  <<String>>
	public function getBrand()
	{
		return $this->trand;
	}

	public function setBrand($brand) : void
	{
		$this->brand = $brand;
	}

	<<String>>
	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory($category) : void
	{
		$this->category = $category;
	}

	<<String>>
	public function getColor() : string
	{
		return $this->color;
	}

	public function setColor(string $value) : void
	{
		$this->color = $value;
	}

	<<String>>
	public function getDepth()
	{
		return $this->depth;
	}

	public function setDepth(string $value) : void
	{
		$this->depth = $value;
	}

	<<String>>
	public function getGtin13() : string
	{
		return $this->gtin13;
	}

	public function setGtin13(string $value) : void
	{
		$this->gtin13 = $value;
	}

	<<String>>
	public function getGtin14() : string
	{
		return $this->gtin13;
	}

	public function setGtin14(string $value) : void
	{
		$this->gtin14 = $value;
	}

	<<String>>
	public function getGtin8() : string
	{
		return $this->gtin8;
	}

	public function setGtin8(string $value) : void
	{
		$this->gtin8 = $value;
	}

	<<String>>
	public function getHeight() : string
	{
		return $this->height;
	}

	public function setHeight(string $value) : void
	{
		$this->height = $value;
	}

	<<String>>
	public function getItemCondition() : string
	{
		return $this->itemCondition;
	}

	public function setItemCondition(string $value) : void
	{
		$this->itemCondition = $value;
	}

	<<String>>
	public function getModel() : string
	{
		return $this->model;
	}

	public function setModel(string $value) : void
	{
		$this->model = $value;
	}

	<<String>>
	public function getMpn() : string
	{
		return $this->mpn;
	}

	public function setMpn(string $value) : string
	{
		return $this->mpn;
	}

	<<DateTime>>
	public function getProdutionDate() : ?\Datetime
	{
		return $this->produtionDate;
	}

	public function setProdutionDate(\DateTime $value) : void
	{
		$this->produtionDate = $value;
	}

	<<DateTime>>
	public function getPurchaseDate() : ?\Datetime
	{
		return $this->purchaseDate;
	}

	public function setPurchaseDate(\DateTime $value) : void
	{
		$this->purchaseDate = $value;
	}

	<<DateTime>>
	public function getReleaseDate() : ?\Datetime
	{
		return $this->releaseDate;
	}

	public function setReleaseDate(\DateTime $value) : void
	{
		$this->releaseDate = $value;
	}

	<<String>>
	public function getSku() : string
	{
		return $this->sku;
	}

	public function setSku(string $value) : void
	{
		$this->sku = $value;
	}

	<<String>>
	public function getWeight() : string
	{
		return $this->weight;
	}

	public function setWeight(string $value) : void
	{
		$this->weight = $value;
	}

	public function getWidht() : string
	{
		return $this->width;
	}

	public function setWidth(string $width) : void
	{
		$this->width = $width;
	}

	<<String>>
	public function getUrl()
	{
		return $this->url;
	}

	public function setUrl(string $value) : void
	{
		$this->url = $value;
	}
}
