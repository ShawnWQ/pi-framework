<?hh

namespace Pi\ServiceInterface;


use Pi\Interfaces\IContainable;
use Pi\Interfaces\IContainer;
use Pi\ServiceModel\SendEmailResponse;




class SmtpMailProvider extends AbstractMailProvider implements IContainable{

	public function ioc(IContainer $container)
	{

	}

	
	public function init()
	{

	}

	protected function getTransport()
	{
		return is_string($this->getSsl())
			? \Swift_SmtpTransport::newInstance($this->getHost(), $this->getPort(), $this->getSsl())
			: \Swift_SmtpTransport::newInstance($this->getHost(), $this->getPort());
	}

	protected function getMailer()
	{
		$transport =  $this->getTransport();

		if(is_string($this->getUsername())) {
			$transport->setUserName($this->getUsername());
		}
		
		if(is_string($this->getPassword())) {
			$transport->setPassword($this->getPassword());
		}
		return  \Swift_Mailer::newInstance($transport);
	}

	protected function addUndeliveredEmail() : void
	{
		
	}


	public function send(string $toName, string $toEmail, string $subject, string $body, bool $isHtml = true) : SendEmailResponse
	{
		$fromName = $this->getFromName();
		$fromEmail = $this->getFromEmail();

		try {
			$mailer = $this->getMailer();
			$message =  \Swift_Message::newInstance($subject)
				->setFrom($this->getFromEmail())
				->setTo(array($toEmail => $toName))
				->setBody($body);

			$result = $mailer->send($message);
			$this->assertMessageResult($result);
		}
		catch(\Exception $ex) {
			$this->addUndeliveredEmail();
			throw $ex;
		}

		return new SendEmailResponse();
	}

	protected function assertMessageResult() : void
	{

	}
}