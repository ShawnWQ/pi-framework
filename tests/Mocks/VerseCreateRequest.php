<?hh

namespace Mocks;

class VerseCreateRequest {

  public $book;

  public $chapter;

  public $number;

  public $text;

  public function __construct()
  {

  }
  public function setBook($book)
  {
    $this->book = $book;
  }
  public function setChapter($ch)
  {
    $this->chapter = $ch;
  }
  public function setNumber($number)
  {
    $this->number = $number;
  }
  public function setText($text)
  {
    $this->text = $text;
  }

  public function number($value = null)
  {
    if($value === null) return $this->number;
    $this->number = $value;
  }

  public function text($value = null)
  {
    if($value === null) return $this->text;
    $this->text = $value;
  }

  public function book($value = null)
  {
    if($value === null) return $this->book;
    $this->book = $value;
  }
  <<Field,Validation,int>>
  public function getBook()
  {
    return $this->book;
  }
  <<Field,Validation,int>>
  public function getChapter()
  {
    return $this->chapter;
  }
  <<Field,Validation,int>>
  public function getNumber()
  {
    return $this->number;
  }
  <<Field,Validation,string,minLength(2)>>
  public function getText()
  {
    return $this->text;
  }
}
