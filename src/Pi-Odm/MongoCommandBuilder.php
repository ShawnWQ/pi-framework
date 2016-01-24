<?hh

namespace Pi\Odm;
use Pi\Odm\MongoCommand;
use Pi\Odm\ConnectionState;
use Pi\Odm\MongoAdapter;

class MongoCommandBuilder {

  protected $insertCommand = null;

  protected $updateCommand;

  protected $deleteCommand;

  protected $collectionName;

  protected $disposed;

  protected $dbSchemaCollection;

  public function __construct(protected MongoAdapter $adapter)
  {

  }

  private function getCollectionName()
  {
    // Secure this first!!!
    return $this->collectionName;
  }


  public function getInsertCommand() : MongoCommand
  {
    if(is_null($this->insertCommand)){
      return $this->createInsertCommand();
    }

    return $this->insertCommand;
  }

  private function getSourceCommand()
  {
    if(!is_null($this->adapter)){
      return $this->adapter->getSelectCommand();
    }
  }

  private function buildCache($closeConnection = true)
  {
    $command = $this->getSourceCommand();
    if(is_null($command)){
      throw new \Exception('The DataAdapter.SelectCommand property needs to be initialized.');
    }

    $connection = $command->getConnection();
    if(is_null($connection)){
      throw new \Exception('The DataAdapter.SelectCommand.Connection property needs to be initialized.');
    }

    // Just build the information if not build yet
    if(is_null($this->dbSchemaCollection)) {
      // Assert connection is opened
      if($connection->getState() == ConnectionState::Open){
        $closeConnection = false;
      } else {
        $connection->open();
      }

      $reader = $command->executeReader('SCHEMA');
      $schema = $reader->getSchemaCollection();
      $reader->close();

      if($closeConnection){
        $connection->close();
      }

      $this->buildInformation($schema);
    }
  }

  private function buildInformation($schemaCollection) : void
  {
    $this->collectionName = $schemaCollection['collectionName'];

    $this->dbSchemaCollection = $schemaCollection;
  }

  private function createNewCommand(&$command) : void
  {
    $sourceCommand = $this->getSourceCommand();

    if(is_null($command)){
      $command =  $sourceCommand->getConnection->createCommand();
      $command->setCommandTimeout($sourceCommand->getTimeout());
      $command->setTransaction($sourceCommand->getTransaction());
    }

    $command->setCommandType('CommandType.Text');
    $command->setUpdatedDocumentSource = 'UpdatedDocumentSource.None';
    $command->getParameters()->clear();
  }

  private function createInsertCommand() : MongoCommand
  {
    if(empty($this->getCollectionName())){
      throw new \Exception('Collection name not set for MongoCommandBuilder');
    }
    $this->createNewCommand($this->insertCommand);
    $command = 'INSERT INTO COLNAME';
    $query = '';

    $this->insertCommand->setCommandText($query);

    return $this->insertCommand;
  }
}
