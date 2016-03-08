<?hh

namespace Pi\ServiceInterface;

use Pi\ServiceModel\ContentPublish;




class AbstractContentPublisher {
	
	public abstract function publish(ContentPublish $obj);

}