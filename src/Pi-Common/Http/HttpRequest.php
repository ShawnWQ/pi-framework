<?hh

namespace Pi\Common\Http;

class HttpRequest {
	
	protected array $cookies;

	protected array $headers;

	protected array $postFields;

	protected array $queryData;

	const METHOD_GET = 'GET';

	const METHOD_POST = 'POST';

	const METHOD_PUT = 'PUT';

	const METHOD_DELETE = 'DELETE';

	public function __construct(protected string $uri, protected string $method = 'GET')
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

	public function getMethod() : string
	{
		return $this->method;
	}

	public function getResponseData()
	{

	}
	public function addCookies(array $cookies)
	{
		$this->cookies = array_merge($this->cookies, $cookies);
	}

	public function getCookies() : array
	{
		return $this->cookies;
	}

	public function addHeaders(array $headers)
	{
		$this->headers = array_merge($this->headers, $headers);
	}

	public function getHeaders() : array
	{
		return $this->headers;
	}

	public function addPostFields(array $fields)
	{
		$this->postFields = array_merge($this->postFields, $fields);
	}

	public function addQueryData(array $dataParams)
	{

	}

	public function send() : HttpMessage
	{
		switch ($this->method) {
			case self::METHOD_GET:
				return $this->handleGetRequest();
				break;

			case self::METHOD_POST:
				return $this->handlePostRequest();
			
			default:
				return $this->handleGetRequest();
				break;
		}
	}

	protected function handleGetRequest() : HttpMessage
	{
		$buffer = file_get_contents($this->uri);
		$message = new HttpMessage($buffer, $this);
		return $message;
	}

	protected function handlePostRequest() : HttpMessage
	{
		$ch = curl_init();
		$fields_string = '';
		foreach($this->postFields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');
		curl_setopt($ch, CURLOPT_URL, $this->uri);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

		// in real life you should use something like:
		// curl_setopt($ch, CURLOPT_POSTFIELDS, 
		//          http_build_query(array('postvar1' => 'value1')));

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);
		$message = new HttpMessage($server_output, $this);
		return $message;
	}
}