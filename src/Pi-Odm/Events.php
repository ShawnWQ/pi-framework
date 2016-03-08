<?hh

namespace Pi\Odm;

enum Events : string {
  PreAggregate  = 'collectionPreAggregate';
  PostAggregate = 'collectionPostAggregate';

  PreLoad = 'preLoad';

  PreConnect = 'preConnect';
  PostConnect = 'postConnect';

  PreInsert = 'preInsert';
  PostInsert = 'postInstert';

  PreUpdate = 'preUpdate';
  PostUpdate = 'postUpdate';

  LoadClassMetadata = 'loadClassMetadata';

  PreRemove = 'preRemove';
  PostRemove = 'postRemove';
}
