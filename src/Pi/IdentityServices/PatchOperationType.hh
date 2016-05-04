<?hh

enum PatchOperationType : string {
	/**
	 * @description
	 * The "add" operation is used to add a new attribute value to an existing resource.
	 * The operation MUST contain a "value" member whose content specifies the value to be added.
	 * The value MAY be a quoted value OR it may be a JSON object containing the sub-attributes of the complex attribute specified in the operation's "path".
	 */
	ADD = 'add';

	/**
	 * @description
	 * The "remove" operation removes the value at the target location specified by the required attribute "path".
	 */
	REMOVE = 'remove';

	/**
	 * @description
	 * The "replace" operation replaces the value at the target location specified by the "path".
	 */
	REPLACE = 'replace';
}
