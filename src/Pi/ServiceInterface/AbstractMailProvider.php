<?hh

namespace Pi\ServiceInterface;

use Pi\HostConfig,
	Pi\Interfaces\ICacheProvider,
	Pi\Interfaces\IContainer,
	Pi\ServiceModel\SendEmailResponse;



abstract class AbstractMailProvider {

	const DEFAULT_MAILPROVIDER_HEADER= '<div>header</div>';
	
	const DEFAULT_MAILPROVIDER_FOOTER = '<div>footer</div>';

	const KEY_MAILPROVIDER_HEADER = 'mailprovider::body';

	const KEY_MAILPROVIDER_FOOTER = 'mailprovider::footer';

	const KEY_MAILPROVIDER_USERNAME = 'mailprovider::username';

	const KEY_MAILPROVIDER_PASSWORD = 'mailprovider::password';

	const KEY_MAILPROVIDER_SSL = 'mailprovider::ssl';

	const KEY_MAILPROVIDER_UNDELIVERED = 'mailprovider::undelivered';

	protected string $bodyHeader;

	protected string $bodyFooter;

	protected ?string $ssl;

	protected ?string $username;

	protected ?string $password;

	public function __construct(
		protected HostConfig $config,
		protected ICacheProvider $cache
	)
	{
		$this->loadFromCache();
	}

	public abstract function send(string $toName, string $toEmail, string $subject, string $body, bool $isHtml = true) : SendEmailResponse;

	public function loadFromCache()
	{
		$this->ssl = $this->cache->get(self::KEY_MAILPROVIDER_SSL);
		$this->bodyHeader = $this->cache->get(self::KEY_MAILPROVIDER_HEADER);
		$this->bodyFooter = $this->cache->get(self::KEY_MAILPROVIDER_FOOTER);
		$this->username = $this->cache->get(self::KEY_MAILPROVIDER_USERNAME) ?: '';
		$this->password = $this->cache->get(self::KEY_MAILPROVIDER_PASSWORD)?: '';
	}

	public function isCached() : bool
	{
		return is_string($this->cache->get(self::KEY_MAILPROVIDER_HEADER)) && is_string($this->cache->get(self::KEY_MAILPROVIDER_FOOTER));
	}

	public function configure(IContainer $container)
	{
		$this->setDefault();
	}
	public function setDefault()
	{
		$this->cache->set(self::KEY_MAILPROVIDER_HEADER, self::DEFAULT_MAILPROVIDER_HEADER);
		$this->cache->set(self::KEY_MAILPROVIDER_FOOTER, self::DEFAULT_MAILPROVIDER_FOOTER);
		$this->cache->set(self::KEY_MAILPROVIDER_USERNAME, $this->config->smtpUsername());
		$this->cache->set(self::KEY_MAILPROVIDER_PASSWORD, $this->config->smtpPassword());
		
		if(is_string($this->config->smtpSsl())) {
			$this->cache->set(self::KEY_MAILPROVIDER_SSL, $this->config->getSmtpSsl());

		}
	}
	public function update(?string $header = null, ?string $footer = null)
	{
		if(!is_null($header)) {
			$this->updateHeader($header);
			$this->bodyHeader = $header;
		}

		if(!is_null($footer)) {
			$this->updateFooter($footer);
			$this->bodyFooter = $footer;
		}
	}

	public function updateHeader(string $header)
	{
		$this->cache->set(self::KEY_MAILPROVIDER_HEADER, $header);
	}

	public function updateFooter(string $footer)
	{
		$this->cache->set(self::KEY_MAILPROVIDER_FOOTER, $footer);
	}

	public function getFromName()
	{
		return $this->config->smtpFromName();
	}

	public function getFromEmail()
	{
		return $this->config->smtpFromEmail();
	}

	public function getHost()
	{
		return $this->config->smtpHost();
	}

	public function getPort()
	{
		return $this->config->smtpPort();
	}

	public function getBodyHeader() : string
	{
		return $this->bodyHeader;
	}

	public function getBodyFooter() : string
	{
		return $this->bodyFooter;
	}

	public function getUsername() : ?string
	{
		return $this->username;
	}

	public function getPassword() : ?string
	{
		return $this->password;
	}

	public function getSsl()
	{
		return $this->ssl;
	}
	
}