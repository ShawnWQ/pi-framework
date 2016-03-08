<?hh

namespace Pi;

enum HttpMethod : string {
  GET = 'GET';
  POST = 'POST';
  PUT = 'PUT';
  DELETE = 'DELETE';
  PATCH = 'PATCH';
  OPTIONS = 'OPTIONS';
  OVERRIDE = '_METHOD';
  HEAD = 'HEAD';
}
