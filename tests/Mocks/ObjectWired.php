<?hh

namespace Mocks;

use Mocks\DumpDependency;

class ObjectWired {

    <<Inject('DumbDependency')>>
    public function setDep($dep){
      $this->dep = $dep;
    }

    public function dep()
    {
      return $this->dep;
    }

    protected $dep;
  }
