<?hh

namespace Pi\Common;

class ClassUtils {

  public static function getClassRealname($className)
  {
    $name = is_object($className) ? get_class($className) : $className;

    if ($pos = strrpos($name, '\\')) return substr($name, $pos + 1);
    return $pos;
  }

  public static function getMethodName($name) : string
  {
      if(substr($name, 0, 3 ) === "get")
      {
          return lcfirst(explode("get", $name)[1]);
      } else {
          return $name;
      }
  }

  /**
   * @return array Array to cache the common properties
   */
  public static function mapDto($entity, $dto, $convertIdToString = false)
  {
  	$response = array();
  	  $rc = new \ReflectionClass($entity);
  	  $dtoRefl = new \ReflectionClass($dto);

      foreach($rc->getProperties() as $prop)
      {
      	
        $name = $prop->getName();

        try {
        	$dtoProp = $dtoRefl->getProperty($name);
        }
        catch(\Exception $ex) {
        	continue;
        }

        if(is_null($dtoProp)) {
        	continue;
        }

        $prop->setAccessible(true);
    	$value = $prop->getValue($entity);
    	if(is_null($value)) {
    		$prop->setAccessible(false);
    		continue;
    	}
    	
		$dtoProp->setAccessible(true);
    if($convertIdToString && ($name === 'id' || $name === '_id')) {
      
      $value = (string)$value;
    } else {

    }
    	$dtoProp->setValue($dto, $value);

    	$prop->setAccessible(false);
    	$dtoProp->setAccessible(false);
    	$response[] = $name;

          
   //    if(!is_array($params) || count($params) == 0 || is_null($params[0]->getClass()))

	 }
 }
}
