<?hh

namespace Pi\ServiceModel;

use Pi\Response;




class GetApplicationMailResponse extends Response {

	protected ApplicationMailDto $mail;


	public function setMail(ApplicationMailDto $dto)
	{
		$this->mail = $dto;
	}

	public function getMail()
	{
		return $this->mail;
	}
}