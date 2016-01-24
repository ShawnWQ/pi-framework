<?hh
namespace Pi;

/**
 * Enumerator for HTTP Status code
 */
enum HttpStatusCode : int {

    Ok = 200;
    Found = 302;
    NotFound = 404;
    BadReques = 500;
}