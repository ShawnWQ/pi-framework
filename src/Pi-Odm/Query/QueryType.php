<?hh

namespace Pi\Odm\Query;

enum QueryType : int {
  TYPE_FIND            = 1;
  TYPE_FIND_AND_UPDATE = 2;
  TYPE_FIND_AND_REMOVE = 3;
  TYPE_INSERT          = 4;
  TYPE_UPDATE          = 5;
  TYPE_REMOVE          = 6;
  TYPE_GROUP           = 7;
  TYPE_MAP_REDUCE      = 8;
  TYPE_DISTINCT        = 9;
  TYPE_GEO_NEAR        = 10;
  TYPE_COUNT           = 11;

  HINT_REFRESH = 33;
}
