<?hh

namespace Mocks;
use Pi\Service;
use Mocks\VerseGet;
use Mocks\VerseById;
use Mocks\VerseCreateRequest;
use Mocks\VerseCreateResponse;
use Mocks\VerseGetResponse;
use Mocks\DumbRequest;
class VerseService extends Service {


	public function __construct(protected DumbDependency $dump = null)
	{

	}

	<<Request,Method('GET'),Route('/dumb')>>
	public function getDumbDependency(DumbRequest $request)
	{
		if($this->dump === null) 
			throw new \Exception('DumbDependency not injected');

		$this->dump->setDumb('1');
		return $this->dump;
	}
}