<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IDbDataAdapter;
use Pi\Odm\Interfaces\IDbCommand;
use Pi\Odm\Events\DocumentUpdatingEventArgs;


abstract class DataAdapter
  implements IDbDataAdapter {

    public abstract function deleteCommand() : IDbCommand;

    public abstract function selectCommand() : IDbCommand;

    public abstract function insertCommand() : IDbCommand;

    public abstract function updateCommand() : IDbCommand;

    protected $deleteCommand;

    protected $selectCommand;

    protected $insertCommand;

    protected $updateCommand;
    /**
     *
     * @var \Pi\EventManager
     */
    protected $eventManager;

    protected $disposing;

    protected function createDocumentUpdatedEvent($dataDocument, IDbCommand $dbCommand, $statementType, $documentMapping)
    {

    }

    protected function onDocumentUpdatedEvent(DocumentUpdatingEventArgs $value)
    {
      $delegates = $this->eventManager->getInvocationList("DocumentUpdated");
      foreach($delegates as $delegate){
        $delegate->invoke($value, null);
      }
    }

    public function dispose()
    {

    }

    protected function disposing() : void
    {
      if($this->disposing){
        $adapter = $this;

        if(!is_null($adapter->updateCommand())){
          $adapter->updateCommand()->dispose();
        }
      }
    }

    protected function fill($dataSet, int $startRecord, int $maxRecords, string $srcTable, IDbCommand $command, $behavior)
    {
      if(is_null($command->getConnection())){
        throw new \Exception('Connection state is closed');
      }

      // Open connection if closed
      if($command->getConnection()->getState() == ConnectionState::Closed){
        $command->getConnection()->open();
      }
    }
}
