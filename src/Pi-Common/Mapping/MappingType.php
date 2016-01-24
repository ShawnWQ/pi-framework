<?hh

namespace Pi\Common\Mapping;

enum MappingType : string {
  Bin = 'Bin';
  Bool = 'Boolean';
  Set = 'Set';
  Date = 'Date';
  Float = 'Float';
  Id = 'Id';
  Int = 'Int';
  ObjectId = 'ObjectId';
  String = 'String';
  Timestamp = 'Timestamp';
  Embeded = 'Embeded';
  ReferenceOne = 'ReferenceOne';
  ReferenceMany = 'ReferenceMany';
}
