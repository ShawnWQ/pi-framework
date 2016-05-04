<?hh

namespace Pi;

class HttpResultUtils {

	public static function success($response)
	{
		return new HttpResult($response, 200);
	}

	public static function validationError($response)
	{
		return new HttpResult($response, 500);
	}
}