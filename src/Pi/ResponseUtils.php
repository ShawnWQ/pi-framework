<?hh

namespace Pi;

/**
 * Response Utilities
 *
 * Utilities methods to return a diferent response than expected (mostly errors)
 */
class ResponseUtils {

	public function invalid($message, $validationResult)
	{

	}

	public function error($message = 'Sorry ')
	{
		return HttpResultUtils::validationError($message);
	}

	public function internal($message = 'Internal')
	{
		return HttpResultUtils::validationError($message);
	}
}
