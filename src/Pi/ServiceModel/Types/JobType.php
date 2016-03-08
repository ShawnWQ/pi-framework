<?hh

namespace Pi\ServiceModel;

enum JobType : string {
  Permanent = 'permanent';
  Unspecified = 'unspecified';
  Contract = 'contract';
}
