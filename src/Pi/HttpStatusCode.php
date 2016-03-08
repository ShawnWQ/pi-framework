<?hh
namespace Pi;

/**
 * Enumerator for HTTP Status code
 */
enum HttpStatusCode : int {

    Ok = 200;
    Found = 302;
    InternalError = 500;
    NotFound = 404;
    BadRequest = 400;
}