<?hh

namespace Pi\Odm\Interfaces;

interface IMannager {

  public function queryBuilder() : IQueryBuilder;

  public function update($update = true);

  public function field($fieldName);

  public function set($value);

  public function unset($value);

  public function select(array $fields);

  public function execute();
}
