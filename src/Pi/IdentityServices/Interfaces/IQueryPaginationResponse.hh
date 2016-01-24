<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IQueryPaginationResponse {

	/**
	 * @description
	 * Non-negative Integer. Specifies the number of query results returned in a query response page;
	 * e.g., 10.
	 */
	public function getItemsPerPage();

	/**
	 * @description
	 * Non-negative Integer. Specifies the total number of results matching the client query; e.g., 1000.
	 */
	public function getTotalResults();

	/**
	 * @description
	 *  The 1-based index of the first result in the current set of query results; e.g., 1.
	 */
	public function getStartIndex();
}