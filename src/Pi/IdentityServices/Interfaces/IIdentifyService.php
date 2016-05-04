<?php
namespace Pi\IdentitiServices\Interfaces;
/**
 * @description
 * Interface for SCIM services. Methods are services
 * Implements identity management services
 */
interface IIdentifyService {
	public function createUser();
	public function modifyUser();
	public function deleteUser();
	public function retrieveUser();

	public function createGroup();
	public function modifyGroup();
	public function deleteGroup();
	public function retrieveGroup();

	public function changePassword();
}