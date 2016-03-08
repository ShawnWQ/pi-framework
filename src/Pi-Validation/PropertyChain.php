<?hh

namespace Pi\Validation;

class PropertyChain {

	public function __construct(protected ?Vector<string> $memberNames = null)
	{
		if($this->memberNames === null) {
			$this->memberNames = Vector {};
		}
	}

	public function getMemberNames()
	{
		return $this->memberNames;
	}

	public function fromParent(PropertyChain $parent)
	{
		$this->memberNames = $parent->getMemberNames();
	}

	public function add($memberInfo) {
		$this->memberNames->add($memberInfo);
	}

	public function buildPropertyName(string $propertyName)
	{
		$chain = new PropertyChain();
		$chain->fromParent($this);
		$chain->add($propertyName);
		return $chain;
	}

	// Checks if the current chain is the child of another chain.
	public function isChildChainOf(PropertyChain $parentChain)
	{

	}

	public function count() : int
	{
		return count($this->memberNames);
	}
}