<?hh

namespace Pi\Common\Mapping;

enum HydratorAutoGenerate : string {
  Never = 'never';
  Always = 'always';
  FileNotExists = 'notfound';
}
