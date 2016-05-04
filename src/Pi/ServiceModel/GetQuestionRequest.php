<?hh

namespace Pi\ServiceModel;

class GetQuestionRequest {

	protected \MongoId $id;

  protected bool $answers = false;

	<<ObjectId>>
	public function getId()
	{
		return $this->id;
	}

	public function setId(\MongoId $id)
	{
		$this->id = $id;
	}

  <<Bool>>
  public function getAnswers()
	{
		return $this->answers;
	}

	public function setAnswers(bool $retrieve)
	{
		$this->answers = $retrieve;
	}
}
