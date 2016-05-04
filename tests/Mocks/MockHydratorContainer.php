<?hh

namespace Mocks;

use Pi\EventManager;




class MockHydratorContainer {
	
	
	
	static function init()
	{
		if(!MockContainer::$initialized) {
			MockContainer::init();
		}
		
		
	}
    
}