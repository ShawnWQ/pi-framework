<?hh

namespace Mocks;
<<MappedSuperclass,Collection('adt-base')>>
abstract class ADTBase {

	protected string $text;

	protected int $number;

	protected ?\DateTime $date;

	protected array $collection;

	protected $id;

	public function __construct()
	{
		$this->collection = array();
		$this->text = 'initial';
		$this->number = 0;
	}

	public function set(string $text, int $number, ?\DateTime $date = null)
	{
		$this->text = $text;
		$this->number = $number;
		if(!$date != null) {
			$this->date = $date;
		}
	}

	<<Collection>>
	public function getCollection() : array
	{
		return $this->collection;
	}

	<<String>>
	public function getText() : string
	{
		return $this->text;
	}

	<<Int,NotNull>>
	public function getNumber() : int
	{
		return $this->number;
	}

	<<DateTime,Date>>
	public function getDate() : ?\DateTime
	{
		return $this->date;
	}

	<<Id>>
	public function getId()
	{
		return $this->id;
	}
}
