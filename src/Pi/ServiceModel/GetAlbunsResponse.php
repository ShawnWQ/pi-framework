<?hh

namespace Pi\ServiceModel;

use Pi\Response;

class GetAlbunsResponse extends Response  {

	protected $albuns;

	public function getAlbuns()
	{
		return $this->albuns;
	}

	public function setAlbuns($albuns)
	{
		$this->albuns = $albuns;
	}
}