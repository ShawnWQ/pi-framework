<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\Interfaces\IDbCommand;

/**
 * Represents a set of command-related properties that are used to fill the DataSet and update a data source
 */
interface IDataAdapter {

    public function deleteCommand() : IDbCommand;

    public function selectCommand() : IDbCommand;

    public function insertCommand() : IDbCommand;

    public function updateCommand() : IDbCommand;
}
