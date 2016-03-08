<?hh
namespace Pi\Odm\Interfaces;
use Pi\Odm\Query\UpdateQueryBuilder;

interface ICollectionRepository <T> {

	public function get($id) : T;
  	public function update(UpdateQueryBuilder $builder) : IDataOperationResult;
	public function remove($id);
	public function find() : array;
	public function getAs($id, string $className);
}
