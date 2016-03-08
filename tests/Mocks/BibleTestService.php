<?hh

namespace Mocks;
use Pi\Service;
use Mocks\VerseGet;
use Mocks\VerseById;
use Mocks\VerseCreateRequest;
use Mocks\VerseCreateResponse;
use Mocks\VerseGetResponse;

class BibleTestService extends Service {
    <<Request,Method('POST'),Route('/test')>>
    public function post(VerseCreateRequest $request)
    {
      $response = new VerseCreateResponse();
      return $response;
    }

    <<Request,Method('GET'),Route('/verse/:id')>>
    public function getById(VerseById $request)
    {
      $response = new VerseGetResponse();
      return $response;
    }
    <<Request,Method('GET'),Route('/test')>>
    public function get(VerseGet $request)
    {
      $response = new VerseGetResponse();
      return $response;
    }
}
