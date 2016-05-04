<?hh

namespace Pi;

use Pi\ServiceInterface\Data\AlbumRepository;

class TestService extends Service {
	
	public function __construct(
		protected AlbumRepository $repo
	)
	{
		if(is_null($repo)) {
			throw new \Exception('repository cant be null');
		}
	}
}