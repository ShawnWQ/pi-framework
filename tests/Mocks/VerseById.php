<?hh

namespace Mocks;
<<Route('/verse/:id')>>
class VerseById {

	public function id($value = null)
  {
    if($value === null) return $this->id;
    $this->id = $value;
  }

  protected $id;
}
