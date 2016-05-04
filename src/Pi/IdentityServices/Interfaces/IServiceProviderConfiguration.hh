<?hh
namespace Pi\IdentitiServices\Interfaces;
interface IServiceProviderConfiguration {

	/**
	 * @description
	 * An HTTP addressable URL pointing to the Service Provider's human consumable help documentation.
	 */
	public function getDocumentationUrl();

	/**
	 * @description
	 * A complex type that specifies PATCH configuration options. 
	 * @required
	 */
	public function getPatch();

	/**
	 * @description
	 * Boolean value specifying whether the operation is supported. 
	 * @required
	 */
	public function getPatchSupported();

	/**
	 * @description
	 * A complex type that specifies BULK configuration options.
	 * @required
	 */
	public function getBulk();

	/**
	 * @description
	 * Boolean value specifying whether the operation is supported.
	 */
	public function getBulkSupported();

	/**
	 * @description
	 * An integer value specifying the maximum number of operations. 
	 * @required
	 */
	public function getBulkMaxOperations();

	/**
	 * @description
	 * An integer value specifying the maximum payload size in bytes. 
	 * @required
	 */
	public function getBulkMaxPayloadSize();

	/**
	 * @description
	 * A complex type that specifies FILTER options.
	 *
	 * @required
	 */
	public function getFilter();

	/**
	 * @description
	 * Boolean value specifying whether the operation is supported.
	 * @required
	 */
	public function getFilterSupported();

	/**
	 * @description
	 * Integer value specifying the maximum number of Resources returned in a response. 
	 * @required
	 */
	public function getFilterMaxResults();

	/**
	 * @description
	 * A complex type that specifies Change Password configuration options. 
	 * @required
	 */
	public function getChangePassword();

	/**
	 * @description
	 * Boolean value specifying whether the operation is supported.
	 * @required
	 */
	public function getChangePasswordSupported();

	/**
	 * @description
	 * A complex type that specifies Sort configuration options.
	 * @required
	 */
	public function getSort();

	/**
	 * @description
	 * Boolean value specifying whether sorting is supported.
	 * @required
	 */
	public function getSortSupported();

	/**
	 * @description
	 * A complex type that specifies Etag configuration options
	 * @required
	 */
	public function getEtag();

	/**
	 * @description
	 * Boolean value specifying whether the operation is supported. R
	 * @required
	 */
	public function getEtagSupported();

	/**
	 * @description
	 * A complex type that specifies whether the XML data format is supported.
	 * @required
	 */
	public function getXmlDataFormat();

	/**
	 * @description
	 * Boolean value specifying whether the operation is supported.
	 * @required
	 */
	public function getXmlDataFormatSupported();

	/**
	 * @description
	 * A complex type that specifies supported Authentication Scheme properties.
	 * Instead of the standard Canonical Values for type, this attribute defines the following Canonical Values to represent common schemes: oauth, oauth2, oauthbearertoken, httpbasic, and httpdigest. 
	 * To enable seamless discovery of configuration, the Service Provider SHOULD, with the appropriate security considerations, make the authenticationSchemes attribute publicly accessible without prior authentication.
	 * @required
	 */
	public function getAuthenticationSchemes();	
}