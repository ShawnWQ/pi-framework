<?hh

namespace Pi\Interfaces;




interface IOpeningHoursModel {
	
	public function getCloses();

	public function getDayOfWeek();

	public function getOpens();

	public function getValidFrom();

	public function getValidThrough();
}