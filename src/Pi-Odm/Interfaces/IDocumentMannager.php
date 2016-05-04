<?hh

namespace Pi\Odm\Interfaces;
use Pi\Odm\MongoUpdateQueryBuilder;

interface IDocumentMannager {

	public function queryBuilder($className);

	public function execute();

	public function flush($document);

	
}
