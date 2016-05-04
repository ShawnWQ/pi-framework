<?hh
namespace Pi\IdentitiServices\Interfaces;

interface ICommonSchemaAttribute {

	/**
	 * @description
	 * Unique identifier for the SCIM Resource as defined by the Service Provider. 
	 * Each representation of the Resource MUST include a non-empty id value. 
	 * This identifier MUST be unique across the Service Provider's entire set of Resources. 
	 * It MUST be a stable, non-reassignable identifier that does not change when the same Resource is returned in subsequent requests. 
	 * The value of the id attribute is always issued by the Service Provider and MUST never be specified by the Service Consumer. bulkId: is a reserved keyword and MUST NOT be used in the unique identifier. 
	 * REQUIRED and READ-ONLY.
	 */
	public function getId();

	/**
	 * @description
	 * An identifier for the Resource as defined by the Service Consumer. The externalId may simplify identification of the Resource between Service Consumer and Service provider by allowing the Consumer to refer to the Resource with its own identifier, obviating the need to store a local mapping between the local identifier of the Resource and the identifier used by the Service Provider. Each Resource MAY include a non-empty externalId value. The value of the externalId attribute is always issued be the Service Consumer and can never be specified by the Service Provider. The Service Provider MUST always interpret the externalId as scoped to the Service Consumer's tenant.
	 */
	public function getExternalId();

	/**
	 * @description
	 * Complex attribute containing resource metadata. Those sub-attributes are OPTIONAL
	 *  - created The DateTime the Resource was added to the Service Provider. The attribute MUST be a DateTime. READ-ONLY.
	 *	- lastModified The most recent DateTime the details of this Resource were updated at the Service Provider. If this Resource has never been modified since its initial creation, the value MUST be the same as the value of created. The attribute MUST be a DateTime. READ-ONLY.
	 *	- location The URI of the Resource being returned. This value MUST be the same as the Location HTTP response header. READ-ONLY.
	 * 	- versionThe version of the Resource being returned. This value must be the same as the ETag HTTP response header. READ-ONLY.
	 *	- attributes The names of the attributes to remove from the Resource during a PATCH operation.
	 */
	public function getMeta();
}