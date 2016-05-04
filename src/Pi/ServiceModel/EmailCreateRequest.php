<?hh

namespace Pi\ServiceModel;

class EmailCreateRequest {
	
	
	public function __construct(
		protected string $subject,
		protected string $body,
		protected bool $html,
		protected string $to
	)
	{

	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function getHtml()
	{
		return $this->html;
	}

	public function getTo()
	{
		return $this->to;
	}
}