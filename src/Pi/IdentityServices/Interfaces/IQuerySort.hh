<?hh
namespace Pi\IdentitiServices\Interfaces;
/**
 * @description
 *  Sort is OPTIONAL.  Sorting allows clients to specify the order in which resources are returned by specifying a combination of sortBy and sortOrder URL parameters.
 */
interface IQuerySort {

	public function getSortBy();

	public function getSortOrder();
}