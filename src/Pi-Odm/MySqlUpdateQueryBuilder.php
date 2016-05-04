<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IUpdateQueryBuilder;

class MySqlUpdateQueryBuilder
  implements IUpdateQueryBuilder {

    protected $current;

    protected $commands = [];

    public function field($fieldName)
    {
        $this->current = $fieldName;
    }

    public function set($value)
    {
      $this->commands[] = array(',' => array($this->current, $value));
      unset($this->current);
    }

    public function getQuery()
    {
      return $this->commands;
    }

    public function remove($fieldName)
    {

    }

    public function incr($total = 1)
    {

    }

    public function decr($total = 1)
    {

    }

    public function eq($value)
    {

    }
  }
