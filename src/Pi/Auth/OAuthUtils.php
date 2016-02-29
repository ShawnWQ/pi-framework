<?hh

namespace Pi\Auth;

use Pi\Common\Http\HttpMessage,
	Pi\Common\Http\HttpRequest;




class OAuthUtils {

	public static function urlencodeRfc3986(mixed $input) : mixed 
	{
		if (is_array($input)) {
			return array_map('self::urlencodeRfc3986', $input);
		}
		else if (is_scalar($input)) {
			return rawurlencode($input);
			return str_replace(
				array('+', '~', '='),
				array(' ', '%7E', '%3D'),
				rawurlencode($input)
			);
			return str_replace(
			  '+', 
			  ' ', 
			  str_replace('%7E', '~', rawurlencode($input))
			);
		}
		else {
			return '';
		}
	}

	public static function getNormalizedHttpMethod(string $method) : string 
	{
		return strtoupper($method);
	}

	public static function getNormalizedHttpUrl(string $httpUrl) 
	{
		$parts = parse_url($httpUrl);
		$scheme = (isset($parts['scheme'])) ? $parts['scheme'] : 'http';
	    $port = (isset($parts['port'])) ? $parts['port'] : (($scheme == 'https') ? '443' : '80');
	    $host = (isset($parts['host'])) ? $parts['host'] : '';
	    $path = (isset($parts['path'])) ? $parts['path'] : '';
	    if (($scheme == 'https' && $port != '443')
	        || ($scheme == 'http' && $port != '80')) {
	      $host = "$host:$port";
	    }

	    return "$scheme://$host$path";
	}

	public static function buildHttpQuery($params) {
		if (!$params) {
		    return '';
		  }

		// Urlencode both keys and values
		$keys = self::urlencodeRfc3986(array_keys($params));
		$values = self::urlencodeRfc3986(array_values($params));
		$params = array_combine($keys, $values);

		// Parameters are sorted by name, using lexicographical byte value ordering.
		// Ref: Spec: 9.1.1 (1)
		uksort($params, 'strcmp');

		$pairs = array();
		foreach ($params as $parameter => $value) {
			if (is_array($value)) {
			  // If two or more parameters share the same name, they are sorted by their value
			  // Ref: Spec: 9.1.1 (1)
			  // June 12th, 2010 - changed to sort because of issue 164 by hidetaka
			  sort($value, SORT_STRING);
			  foreach ($value as $duplicate_value) {
			    $pairs[] = $parameter . '=' . $duplicate_value;
			  }
			}
			else {
			  $pairs[] = $parameter . '=' . $value;
			}
		}
		// For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
		// Each name-value pair is separated by an '&' character (ASCII code 38)
		return implode('&', $pairs);
	}

	public static function getSignableParameters($params) 
	{
		// Remove oauth_signature if present
	    // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
	    if (isset($params['oauth_signature'])) {
	      unset($params['oauth_signature']);
	    }
	    return self::buildHttpQuery($params);
	}
}