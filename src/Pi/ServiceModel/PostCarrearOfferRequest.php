<?hh

namespace Pi\ServiceModel;

class PostCarrearOfferRequest {

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

	public function getCompanyLogo()
	{
		return $this->companyLogo;
	}

	public function setCompanyLogo($logo)
	{
		$this->companyLogo = $logo;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function setRoles(array $roles)
	{
		$this->roles = $roles;
	}

	public function getAboutCompany()
	{
		return $this->aboutCompany;
	}

	public function setAboutCompany(string $text)
	{
		$this->aboutCompany = $text;
	}

	public function getCompanyName()
	{
		return $this->companyName;
	}

	public function setCompanyName(string $text)
	{
		$this->companyName = $text;
	}

	public function getJobType()
	{
		return $this->jobType;
	}

	public function setJobType($type)
	{
		$this->jobType = $type;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress(string $value)
	{
		$this->address = $value;
	}

	public function getSkillsRequirements()
	{
		return $this->skillsRequirements;
	}

	public function setSkillsRequirements(string $value)
	{
		$this->skillsRequirements = $value;
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
}
