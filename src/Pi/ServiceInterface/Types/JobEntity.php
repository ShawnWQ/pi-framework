<?hh

namespace Pi\ServiceInterface\Types;

use Pi\Odm\Interfaces\IEntity;

class JobEntity implements IEntity {

	<<Id>>
	public function id($value = null)
	{

	}
	
	protected $title;

	protected $companyName;

	protected JobType $jobType;

	protected JobRemote $jobRemote;

	protected $description;

	protected $skillsAndRequirements;

	/**
	 * Examples: ruby-on-rails jquery c# dba
	 */
	protected $techsAndRoles;

	protected $address;

	protected $aboutCompany;

	protected $companyUrl;

	protected $companyLogoImg;

	protected $allowQuestions;


}