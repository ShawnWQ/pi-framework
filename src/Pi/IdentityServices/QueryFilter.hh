<?hh

enum QueryFilter : string {
	/**
	 * @description
	 * The attribute and operator values must be identical for a match.
	 */
	EQ = 'equal';

	/**
	 * @description
	 * The attribute and operator values are not identical.
	 */
	NE = 'notequal';

	/**
	 * @description
	 *  The entire operator value must be a substring of the attribute value for a match.
	 */
	CO = 'contains';

	/**
	 * @description
	 * The entire operator value must be a substring of the attribute value, starting at the beginning of the attribute value. This criterion is satisfied if the two strings are
	 */
	SW = 'startswith';

	/**
	 * @description
	 * The entire operator value must be a substring of the attribute value, matching at the end of the attribute value.  This criterion is satisfied if the two strings are identical.
	 */
	EW = 'endswith';

	/**
	 * @name Presente or has value
	 * @description
	 * If the attribute has a non-empty or non-null value, or if it contains a non-empty node for complex attributes there
	 */
	PR = 'presente'; // has value

	/**
	 * @name Greather than
	 * @description
	 * If the attribute value is greater than operator value, there is a match.
	 * The actual comparison is dependent on the attribute type.
	 * For string attribute types, this is a lexicographical comparison and for DateTime types, it is a chronological comparison. For Integer attributes it is a comparison by numeric value. Boolean and Binary attributes SHALL cause a failed response (HTTP Status 400) with scimType of invalidFiler.
	 */
	GT = 'greatherthan';

	/**
	 * @name Greather than or equal
 	 * @description
	 * If the attribute value is greater than or equal to the operator value, there is a match.
	 * The actual comparison is dependent on the attribute type.
	 * For string attribute types, this is a lexicographical comparison and for DateTime types, it is a chronological comparison. For Integer attributes it is a comparison by numeric value. Boolean and Binary attributes SHALL cause a failed response (HTTP Status 400) with scimType of invlaidFiler.
	 */
	GE = 'greatherthanorequal';

	/**
	 * @description
	 * If the attribute value is less than operator value, there is a match.
	 * The actual comparison is dependent on the attribute type.
	 * For string attribute types, this is a lexicographical comparison and for DateTime types, it is a chronological comparison. For Integer attributes it is a comparison by numeric value. Boolean and Binary attributes SHALL cause a failed response (HTTP Status 400) with scimType of invalidFiler.
	 */
	LT = 'lowerthan';

	/**
	 * @description
	 * If the attribute value is less than or equal to the operator value, there is a match. The actual comparison is dependent on the attribute type.
	 * For string attribute types, this is a lexicographical comparison and for DateTime types, it is a chronological comparison.
	 * For Integer attributes it is a comparison by numeric value. Boolean and Binary attributes SHALL cause a failed response (HTTP Status 400) with scimType of invalidFiler.
	 */
	LE = 'lowerthanorequal';

	/**
	 * @name Logical And
	 * @description
	 * The filter is only a match if both expressions evaluate to true.
	 */
	AND = 'and';

	/**
	 * @name Logical or
	 * @description
	 * The filter is a match if either expression evaluates to true.
	 */
	OR = 'or';

	/**
	 * @description
	 * The filter is a match if the expression evaluates to false.
	 */
	NOT = 'not';
}
