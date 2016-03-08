<?hh

namespace Pi\Common;

/**
 * Generate a random string based on a length
 */
class RandomString {

  public static function generate($length = 10) : string
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function endsWith(string $text, string $assertion)
  {
    $strlen = strlen($text);
    $testlen = strlen($assertion);

    if ($testlen > $strlen) return false;

    return substr_compare($text, $assertion, $strlen - $testlen, $testlen) === 0;
  }
}
