<?hh

namespace Pi\Common;

class StringUtils {

	/**
	 *
	 * @source http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
	 */
	public function startsWith($haystack, $needle) {
	    // search backwards starting from haystack length characters from the end
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	/**
	 * @source http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
	 */
	public function endsWith($haystack, $needle) {
	    // search forward starting from end minus needle length characters
	    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}

	public static function isNullOrEmptyString(mixed $value) {
		return (!isset($value) || trim($value) === '');
	}

	/**
	 * @source http://stackoverflow.com/questions/5696412/get-substring-between-two-strings-php
	 */
	public function getStringBetween($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	/** 
	 * @source http://stackoverflow.com/questions/828870/php-regex-how-to-get-the-string-value-of-html-tag
	 */
	public function getTextBetweenTags($string, $tagname) {
	    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
	    preg_match($pattern, $string, $matches);
	    return $matches[1];
	}
}
