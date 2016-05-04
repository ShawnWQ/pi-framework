<?hh

namespace Pi\Odm;

enum DocumentState : string {
  NotCreated = 'new';
  Detached = 'detached';
  Managed = 'managed';
}
