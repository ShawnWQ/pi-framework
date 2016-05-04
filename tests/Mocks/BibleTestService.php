<?hh

namespace Mocks;

use Pi\Service,
    Mocks\VerseGet,
    Mocks\VerseById,
    Mocks\VerseCreateRequest,
    Mocks\VerseCreateResponse,
    Mocks\VerseGetResponse;




class BibleTestService extends Service {
    
    <<Method('GET'),Route('/test')>>
    public function get(VerseGet $request)
    {
      $response = new VerseGetResponse();
      return $response;
    }
    
    <<Method('POST'),Route('/test')>>
    public function post(VerseCreateRequest $request)
    {
      $response = new VerseCreateResponse();
      return $response;
    }

    <<Method('GET'),Route('/verse/:id')>>
    public function getById(VerseById $request)
    {
      $response = new VerseGetResponse();
      return $response;
    }
}