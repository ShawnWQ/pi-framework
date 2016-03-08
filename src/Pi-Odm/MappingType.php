<?hh

namespace Pi\Odm;

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
  EmbedOne = 'EmbedOne';
  EmbedMany = 'EmbedMany';
  ReferenceOne = 'ReferenceOne';
  ReferenceMany = 'ReferenceMany';
  DBRef = 'DBRef';
  Collection = 'Collection';
  NotNull = 'NotNull';
  DateTime = 'DateTime';
  InheritanceType = 'InheritanceType';
  DiscriminatorField = 'DiscriminatorField';
  DefaultDiscriminatorValue = 'DefaultDiscriminatorValue';
  MappedSuperclass = 'MappedSuperclass';
}
