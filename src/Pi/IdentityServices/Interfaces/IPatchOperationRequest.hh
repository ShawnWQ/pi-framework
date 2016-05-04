<?php
namespace Pi\IdentitiServices\Interfaces;
interface IPatchOperationRequest {

	/**
	 * @description
	 * The operation name: Ie: add
	 */
	public function getOp();

	/**
	 * @description
	 * String containing an attribute path describing the target of the operation. 
	 * The "path" attribute is OPTIONAL for "add" and "replace" and is REQUIRED for "remove" operations.
	 */
	public function getPath();

	/**
	 * @example
	 * [
     *  {
     *    "display": "Babs Jensen",
     *    "$ref": "https://example.com/v2/Users/2819c223...413861904646",
     *    "value": "2819c223-7f76-453a-919d-413861904646"
     *  }
     * ]
     */
	public function getValue();

}