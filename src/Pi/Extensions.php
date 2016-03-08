<?hh

namespace Pi;
use Pi\Validation\Interfaces\IValidationProperty;
use Pi\Validation\AbstractValidator;
use Pi\Validation\ValidationException;
use Pi\HostConfig,
    Pi\Interfaces\IRequest,
    Pi\Interfaces\IResponse;




class Extensions {

  public static function getHeadersFromCurlResponse($response)
  {
      $headers = array();

      $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

      foreach (explode("\r\n", $header_text) as $i => $line)
          if ($i === 0)
              $headers['http_code'] = $line;
          else
          {
              list ($key, $value) = explode(': ', $line);

              $headers[$key] = $value;
          }

      return $headers;
  }

  /**
   * @url http://stackoverflow.com/questions/14114411/remove-all-special-characters-from-a-string
   */
  public static function hyphenize($string) {
    $dict = array(
        "I'm"      => "I am",
        "thier"    => "their",
    );
    return strtolower(
        preg_replace(
          array( '#[\\s-]+#', '#[^A-Za-z0-9\. -]+#' ),
          array( '-', '' ),
          // the full cleanString() can be download from http://www.unexpectedit.com/php/php-clean-string-of-utf8-chars-convert-to-similar-ascii-char
          self::cleanString(
              str_replace( // preg_replace to support more complicated replacements
                  array_keys($dict),
                  array_values($dict),
                  urldecode($string)
              )
          )
        )
    );
  }

  public static function getOperationName($requestDto)
  {
    return get_class($requestDto);
  }

  public static function testingMode()
  {
    return defined('PHPUNIT_PI_DEBUG') && constant('PHPUNIT_PI_DEBUG') === true;
    //return constant('PHPUNIT_PI_DEBUG') || false;
  }

  public static function protectFn(\Closure $callable)
  {
      return function () use ($callable) {
          return $callable;
      };
  }

  public static function getUrl(HostConfig $config, string $typeShort, $id, string $title, ?string $path = null) 
  {
    $title = self::getUrlName($title, $path);
    return sprintf('%s//%s/%s/%s-%s', $config->protocol(), $config->domain(), $title, $typeShort, $id);
  }

  public static function getUrlName(string $title, $path = null)
  {
    if(!is_null($path)) {
      $title = $title . '_' . $path;
    }
    $title = self::cleanString($title);
    $title = self::hyphenize($title);
    $title = ucwords($title);
    return strtolower($title);
  }

  public static function sanitizeUrl(string $displayName)
  {
    $trimmed = trim($displayName);
    $replaced = str_replace(' ', '-', $trimmed);
    return strtolower($replaced);
  }

  public static function validateInputUrl(?string $url)
  {
    return !is_null($url) && is_string($url) && strlen($url) >= 5;
  }

  public static function nullthrows<T>(?T $x, ?string $message = null): T
  {
    if ($x === null) {
      throw new \Exception($message ?: 'Unexpected null');
    }

    return $x;
  }

  public static function requestHasReturnSession(IRequest $request)
  {
    return isset($request->items()[SessionPlugin::RequestItemsReturnSessionKey])
      && $request->items()[SessionPlugin::RequestItemsReturnSessionKey] == true;
  }

  public function assertValidation(AbstractValidator $validator, $requestDto)
  {
    $result = $validator->validate($requestDto);
    if ($result->isValid()) {
      return $result;
    }
    throw new ValidationException($result);
  }

  public static function assertId($id) : void
  {
    if(!\MongoId::isValid($id)) {
      throw new \Exception(
          sprintf('Invalid Id: %s', $id));
    }
  }

  public static function jsonSerialize(&$entity, $idToStr = true, $idField = '_id')
  {
    $vars = get_object_vars($entity);
    if (array_key_exists($idField, $vars)) {
      $vars[$idField] = $vars[$idField];
    }
    return $vars;
  }

   /**
    * Returns an string clean of UTF8 characters. It will convert them to a similar ASCII character
    * www.unexpectedit.com 
    */
  public static function cleanString($text) {
      // 1) convert á ô => a o
      $text = preg_replace("/[áàâãªä]/u","a",$text);
      $text = preg_replace("/[ÁÀÂÃÄ]/u","A",$text);
      $text = preg_replace("/[ÍÌÎÏ]/u","I",$text);
      $text = preg_replace("/[íìîï]/u","i",$text);
      $text = preg_replace("/[éèêë]/u","e",$text);
      $text = preg_replace("/[ÉÈÊË]/u","E",$text);
      $text = preg_replace("/[óòôõºö]/u","o",$text);
      $text = preg_replace("/[ÓÒÔÕÖ]/u","O",$text);
      $text = preg_replace("/[úùûü]/u","u",$text);
      $text = preg_replace("/[ÚÙÛÜ]/u","U",$text);
      $text = preg_replace("/[’‘‹›‚]/u","'",$text);
      $text = preg_replace("/[“”«»„]/u",'"',$text);
      $text = str_replace("–","-",$text);
      $text = str_replace(" "," ",$text);
      $text = str_replace("ç","c",$text);
      $text = str_replace("Ç","C",$text);
      $text = str_replace("ñ","n",$text);
      $text = str_replace("Ñ","N",$text);
   
      //2) Translation CP1252. &ndash; => -
      $trans = get_html_translation_table(HTML_ENTITIES); 
      $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark 
      $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook 
      $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark 
      $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis 
      $trans[chr(134)] = '&dagger;';    // Dagger 
      $trans[chr(135)] = '&Dagger;';    // Double Dagger 
      $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent 
      $trans[chr(137)] = '&permil;';    // Per Mille Sign 
      $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron 
      $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark 
      $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE 
      $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark 
      $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark 
      $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark 
      $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark 
      $trans[chr(149)] = '&bull;';    // Bullet 
      $trans[chr(150)] = '&ndash;';    // En Dash 
      $trans[chr(151)] = '&mdash;';    // Em Dash 
      $trans[chr(152)] = '&tilde;';    // Small Tilde 
      $trans[chr(153)] = '&trade;';    // Trade Mark Sign 
      $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron 
      $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark 
      $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE 
      $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis 
      $trans['euro'] = '&euro;';    // euro currency symbol 
      ksort($trans); 
       
      foreach ($trans as $k => $v) {
          $text = str_replace($v, $k, $text);
      }
   
      // 3) remove <p>, <br/> ...
      $text = strip_tags($text); 
       
      // 4) &amp; => & &quot; => '
      $text = html_entity_decode($text);
       
      // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
      $text = preg_replace('/[^(\x20-\x7F)]*/','', $text); 
       
      $targets=array('\r\n','\n','\r','\t');
      $results=array(" "," "," ","");
      $text = str_replace($targets,$results,$text);
   
      //XML compatible
      /*
      $text = str_replace("&", "and", $text);
      $text = str_replace("<", ".", $text);
      $text = str_replace(">", ".", $text);
      $text = str_replace("\\", "-", $text);
      $text = str_replace("/", "-", $text);
      */
       
      return ($text);
  } 
}
