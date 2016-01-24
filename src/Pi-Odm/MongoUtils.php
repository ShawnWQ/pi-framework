<?hh

namespace Pi\Odm;
use Pi\Odm\MongoResponseError;

class MongoUtils {

  public static function isError($response)
  {
    return $response['ok'] != 1;
  }

  public static function getErrorDto($response){
    if($response['ok'] === 1){
      return null;
    }

    $error = new MongoResponseError();
    return $error;
  }

  public static function json_decode($data)
  {
    
  }
}
