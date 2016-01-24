<?hh

namespace Pi\ServiceInterface;

use Pi\Service;
use Pi\ServiceModel\NotFoundRequest;

class NotFoundService extends Service {

    <<Request,Method('ANY')>>
    public function any(NotFoundRequest $request)
    {
      $response = array('message' => '404');

      return $response;
    }
}
