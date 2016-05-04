<?hh

namespace Pi\Host;

use Pi\EventManager;
use Pi\Common\Mapping\AbstractMetadataFactory;
use Pi\Odm\Interfaces\IMappingDriver;
use Pi\Host\OperationDriver;
use Pi\Host\Operation;
use Pi\Odm\Interfaces\IEntityMetaDataFactory;

/**
 * Operation Metadata Factory
 *
 * Handle the registration of new Operation instances
 * Loads the metadata into classes
 */
class OperationMetaFactory extends AbstractMetadataFactory implements IEntityMetaDataFactory {

	private $loadedMetadata;

	private $initialized = false;

	public function __construct(
		protected EventManager $eventManager,
		protected OperationDriver $mappingDriver)
	{
		$this->loadedMetadata = Map{};
	}
	
	public function newEntityMetadataInstance(string $documentName)
	{
		return new Operation($documentName);
	}
}
