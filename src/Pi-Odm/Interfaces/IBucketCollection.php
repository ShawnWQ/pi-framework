<?hh

namespace Pi\Odm\Interfaces;

interface IBucketCollection {

  	public function getPosition();

  	public function getLimit();

  	public function getEntityId();

  	public function getEntityName();
}
