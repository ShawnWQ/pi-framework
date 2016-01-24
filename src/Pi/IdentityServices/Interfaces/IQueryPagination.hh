<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IQueryPagination {
	/**
	 * @description
	 * The 1-based index of the first query result. A value less than 1 SHALL be interpreted as 1.
	 * @default 1
	 */
	public function getStartIndex();

	/**
	 * @description
	 * Non negative value. Specifies the desired maximum number of query results per page.
	 *
	 */
	public function getCount();
}