<?hh


namespace Mocks;

use Pi\Interfaces\IContainer;
use Collaboration\CollaborationPlugin;;

class CollaborationMockHost  extends BibleHost {

    public function configure(IContainer $container)
    {
    	parent::configure($container);
        $this->registerPlugin(new CollaborationPlugin());
    }
  }
