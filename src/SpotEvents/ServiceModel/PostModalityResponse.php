<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class PostModalityResponse extends Response {

	protected ModalityDto $modality;

	public function getModality() : ModalityDto
	{
		return $this->modality;
	}

	public function setModality(ModalityDto $dto) : void
	{
		$this->modality = $dto;
	}
}