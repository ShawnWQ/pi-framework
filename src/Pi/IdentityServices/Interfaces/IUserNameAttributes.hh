<?php
namespace Pi\IdentitiServices\Interfaces;

interface IUserNameAttributes {
	/**
	 * @description
	 * The full name, including all middle names, titles, and suffixes as appropriate, formatted for display (e.g. Ms. Barbara Jane Jensen, III.).
	 */
	public function getFormatted();

	/**
	 * @description
	 * The family name of the User, or "Last Name" in most Western languages (e.g. Jensen given the full name Ms. Barbara Jane Jensen, III.).
	 */
	public function getFamilyName();

	/**
	 * @description
	 * The given name of the User, or "First Name" in most Western languages (e.g. Barbara given the full name Ms. Barbara Jane Jensen, III.).
	 */
	public function getGivenName();

	/**
	 * @description
	 * The middle name(s) of the User (e.g. Jane given the full name Ms. Barbara Jane Jensen, III.).
	 */
	public function getMiddleName();

	/**
	 * @description
	 * The honorific prefix(es) of the User, or "Title" in most Western languages (e.g. Ms. given the full name Ms. Barbara Jane Jensen, III.).
	 */
	public function getHonorificPrefix();

	/**
	 * @description
	 * The honorific suffix(es) of the User, or "Suffix" in most Western languages (e.g. III. given the full name Ms. Barbara Jane Jensen, III.).
	 */
	public function getHonorificSuffix();
}