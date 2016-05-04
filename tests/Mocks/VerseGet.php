<?hh

namespace Mocks;
<<Route('/verse')>>
class VerseGet {

	public function getBook()
	{
		return $this->book;
	}

	public function setBook($book)
	{
		$this->book = $book;
	}

	public function getChapter()
	{
		return $this->chapter;
	}

	public function setChapter($chapter)
	{
		$this->chapter = $chapter;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function setNumber($number)
	{
		$this->number = $number;
	}

	protected $book;

	protected $chapter;

	protected $number;
}
