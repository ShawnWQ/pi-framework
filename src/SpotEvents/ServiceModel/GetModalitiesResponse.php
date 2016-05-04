<?hh

namespace SpotEvents\ServiceModel;

use Pi\Response;


class GetModalitiesResponse extends Response {

	protected $modalities;

	public function getModalities()
	{
		return $this->modalities;
	}

	public function setModalities(array $res)
	{
		$this->modalities = $res;
	}
}