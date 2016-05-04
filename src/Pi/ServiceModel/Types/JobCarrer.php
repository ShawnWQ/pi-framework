<?hh

namespace Pi\ServiceModel\Types;

use Pi\Odm\Interfaces\IEntity;

<<Collection('JobCarrer'),MultiTenant>>
class JobCarrer implements IEntity {

	protected \MongoId $id;

	/**
	 * The application title
	*/
	protected string $title;

	/**
	 * The job description
	 */
	protected string $description;

	protected string $skillsRequirements;

	protected string $roles;

	protected string $address;

	protected string $aboutCompany;

	protected string $companyLogo;

	protected array $submitEmails;

	protected string $excerpt;

	protected string $companyName;

	protected JobType $jobType;


	protected array $subscribers;

	<<String>>
	public function getCompanyLogo()
	{
		return $this->companyLogo;
	}

	public function setCompanyLogo($logo)
	{
		$this->companyLogo = $logo;
	}

	<<String>>
	public function getRoles()
	{
		return $this->roles;
	}

	public function setRoles(array $roles)
	{
		$this->roles = $roles;
	}

	<<String>>
	public function getAboutCompany()
	{
		return $this->aboutCompany;
	}

	public function setAboutCompany(string $text)
	{
		$this->aboutCompany = $text;
	}

	<<String>>
	public function getCompanyName()
	{
		return $this->companyName;
	}

	public function setCompanyName(string $text)
	{
		$this->companyName = $text;
	}

	<<String>>
	public function getJobType()
	{
		return $this->jobType;
	}

	public function setJobType($type)
	{
		$this->jobType = $type;
	}

	<<String>>
	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress(string $value)
	{
		$this->address = $value;
	}

	<<String>>
	public function getSkillsRequirements()
	{
		return $this->skillsRequirements;
	}

	public function setSkillsRequirements(string $value)
	{
		$this->skillsRequirements = $value;
	}

	<<Id>>
	public function id($id = null)
	{
		if(is_null($id)) return $this->id;
		$this->id = $id;
	}

	<<String>>
	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle(string $value)
	{
		$this->title = $value;
	}

	<<String>>
	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription(string $value)
	{
		$this->description = $value;
	}

	<<String>>
	public function getExcerpt()
	{
		return $this->excerpt;
	}

	public function setExcerpt(string $value)
	{
		$this->excerpt = $value;
	}

	<<EmbedMany('\MongoId')>>
	public function getSubscribers()
	{
		return $this->subscribers;
	}

	public function setSubscribers($data)
	{
		$this->subscribers = $data;
	}
}
