<?hh

namespace Pi\Odm\Repository;

use Pi\Interfaces\PiScannerInterface;
use Pi\PiFileMapper;

class RepositoryScanner implements PiScannerInterface {
	
		public function __construct(protected PiFileMapper $fileMapper)
	{

	}

	public function getMeta() : Map
	{
		$map = $this->fileMapper->getMap();
		$set = Map{};
		// $fileName file location path
		
		foreach($map as $repositoryType => $fileName) {

			$interfaces = class_implements($repositoryType);
			if(!in_array('Pi\Odm\Interfaces\ICollectionRepository', $interfaces) &&
				!in_array('Pi\Odm\Interfaces\ICollectionRepository<T>', $interfaces)){
				continue;
			}

			$rc = new \ReflectionClass($repositoryType);
			$meta = new RepositoryMeta($repositoryType, array());
			$set[$repositoryType] = $meta;
		}
		return $set;
	}
}