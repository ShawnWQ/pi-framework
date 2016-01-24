<?hh

namespace Pi\Common\Http;

class HttpMessage {

	protected array $cookies;

	protected array $headers;

	protected array $postFields;

	protected array $queryData;

	protected string $requestUrl;

	public function __construct(
		protected string $message,
		protected HttpRequest $request
		)
	{
		$this->reset();
	}

	protected function reset()
	{
		$this->cookies = array();
		$this->headers = array();
		$this->postFields = array();
		$this->queryData = array();
	}

	public function getHeaders()
	{

	}

	public function getBody()
	{
		return $this->message;
	}

	public function getCookies()
	{

	}

	public function getResponseCode() : int
	{

	}

	public function getResponseStatus() : string
	{

	}

	public function getRequestUrl()
	{
		return $this->requestUrl;
	}

	public function getRequestMethod()
	{
		return $this->request->getMethod();
	}
}