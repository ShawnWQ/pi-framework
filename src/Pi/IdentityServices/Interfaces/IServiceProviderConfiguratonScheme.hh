<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IServiceProviderConfiguratonScheme {

	/**
	 * @description
	 * The common authentication scheme name; e.g., HTTP Basic.
	 * @required
	 */
	public function getName();

	/**
	 * @description
	 * A description of the Authentication Scheme. 
	 */
	public function getDescription();

	/**
	 * @description
	 * A HTTP addressable URL pointing to the Authentication Scheme's specification.
	 */
	public function getSpecUrl();

	/**
	 * @description
	 * A HTTP addressable URL pointing to the Authentication Scheme's usage documentation.
	 */
	public function getDocumentationUrl();
}