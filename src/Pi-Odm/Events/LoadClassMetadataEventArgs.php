<?hh

namespace Pi\Odm\Events;
use Pi\EventArgs;
use Pi\Odm\Mapping\EntityMetaData;

class LoadClassMetadataEventArgs extends EventArgs {

  public function __construct(protected EntityMetaData $class, protected $documentManager)
  {

  }
}
