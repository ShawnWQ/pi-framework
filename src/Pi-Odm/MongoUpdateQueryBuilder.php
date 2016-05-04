<?hh

namespace Pi\Odm;
use Pi\Odm\Interfaces\IUpdateQueryBuilder;

class MongoUpdateQueryBuilder
  implements IUpdateQueryBuilder {

    protected $current;

    protected $commands = [];

    public function getCommand()
    {

    }

    public function field($fieldName)
    {
        $this->current = $fieldName;
        return $this;
    }

    public function set($value)
    {
      $this->commands[] = array('$set' => array($this->current, $value));
      unset($this->current);
      return $this;
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
      $this->commands[] = array('$eq' => array($this->current, $value));
      unset($this->current);
      return $this;
    }
  }
