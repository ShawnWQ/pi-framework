<?hh

namespace Pi\Odm\Interfaces;

interface IUpdateQueryBuilder {

  public function field($fieldName);

  public function set($value);

  public function remove($fieldName);

  public function incr($total = 1);

  public function decr($total = 1);

  public function eq($value);

  public function getQuery();
}
