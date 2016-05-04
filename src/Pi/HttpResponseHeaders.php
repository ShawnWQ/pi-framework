<?hh

namespace Pi;

// http://stackoverflow.com/questions/11037004/asp-mvc-are-there-any-constants-for-the-default-http-headers
enum HttpResponseHeaders : string {
	/**
	 *  What partial content range types this server supports
	 */
	AcceptRanges = 'Accept-Ranges';
}