<?hh

namespace Pi\Common\Http;

use Pi\Interfaces\IResponse,
	Pi\Extensions;




/**
 * The client don't follow redirects. If Location header is present, we need to request the new url
 */
class HttpRequest {
	
	protected array $cookies;

	protected array $headers;

	protected array $postFields;

	protected Map $queryData;

	protected ?string $content;

	const METHOD_GET = 'GET';

	const METHOD_POST = 'POST';

	const METHOD_PUT = 'PUT';

	const METHOD_DELETE = 'DELETE';

	protected Vector<(function(IResponse) : void)> $responseFilters;

	protected Vector<(function(IResponse) : void)> $preResponseFilters;

	protected IResponse $response;

	public function __construct(protected string $uri, protected string $method = 'GET')
	{
		$this->responseFilters = Vector{};
		$this->preResponseFilters = Vector{};
		$this->reset();
		$this->response = new PiClientResponse();
	}

	public function responseFilters() : Vector<(function(IResponse) : void)>
	{
		return $this->responseFilters;
	}

	public function preResponseFilters() : Vector<(function(IResponse) : void)>
	{
		return $this->preResponseFilters;
	}

	protected function reset()
	{
		$this->cookies = array();
		$this->headers = array();
		$this->postFields = array();
		$this->queryData = Map{};
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

	public function addHeader(string $key, string $value) : void
	{
		$this->headers[$key] = $value;
	}

	public function getHeaders() : array
	{
		return $this->headers;
	}

	public function addPostFields(array $fields)
	{
		$this->postFields = array_merge($this->postFields, $fields);
	}

	public function addQueryData(Map $params)
	{
		$this->queryData->setAll($params);
	}

	public function addContent(string $content)
	{
		$this->content = $content;
	}

	protected function callResponseFilters() : void
	{
		foreach ($this->responseFilters as $filter) {
			$filter($this->response);
		}
	}

	protected function response() : IResponse
	{
		return $this->response;
	}

	public function send() : HttpMessage
	{
		$msg = null;
		switch ($this->method) {
			case self::METHOD_GET:
				$msg = $this->handleGetRequest();
				break;

			case self::METHOD_POST:
				$msg = $this->handlePostRequest();
			
			default:
				$msg = $this->handleGetRequest();
				break;
		}
		$this->callResponseFilters();
		return $msg;
	}

	protected function getUriWithParams()
	{
		$uri = $this->uri;
		$i = 0;
		foreach ($this->queryData as $key => $value) {
			$uri .= $i == 0
				? '?'.$key.'='.$value
				: '&'.$key.'='.$value;
			$i++;
		}
		return $uri;
	}

	protected function setStreamContextOptsHeaders(array $opts)
	{
		if(count($this->headers) <= 0) {
			return;
		}
	
		$headers = '';
		foreach ($this->headers as $key => $value) {
			$headers .= "$key: $value\r\n" ;
		}
		$opts['http']['headers'] = $headers;	
	}

	protected function handleGetRequest() : HttpMessage
	{
		$uri = $this->content == null ? $this->uri : $this->getUriWithParams();

		$ch = curl_init($uri);
		$fields_string = '';
		foreach($this->postFields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');

		if($this->queryData && count($this->queryData) > 0) {
			foreach($this->queryData as $key=>$value) { 
				$fields_string .= $key.'='.$value.'&'; 
			}
			rtrim($fields_string, '&');			
		}
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt( $ch, CURLOPT_POST, false );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, false );
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch,CURLOPT_HEADER,true);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$res = curl_exec( $ch );
		list($headersRes, $response) = explode("\r\n\r\n", $res, 2);;
		$headers = Extensions::getHeadersFromCurlResponse($headersRes);
		foreach ($headers as $key => $value) {
			$this->response->addHeader((string)$key, (string)$value);
		}	
		curl_close ($ch);
		return new HttpMessage($response, $this);

		$opts = array('http' =>
		    array(
		        'method'  => 'GET',
		        'content' => $this->content
		    )
		);
		$this->setStreamContextOptsHeaders($opts);
		$uri = $this->getUriWithParams();
		$context  = stream_context_create($opts);
		$buffer = file_get_contents($uri, false, $context);
		$headers = $http_response_header;
		foreach ($headers as $value) {
			$key = explode(': ', $value, 2);
			if(count($key) != 2) {
				continue;
			}
			$this->response->addHeader((string)$key[0], (string)$key[1]);
		}
		curl_close ($ch);
		return new HttpMessage($buffer, $this);
	}

	protected function handlePostRequest() : HttpMessage
	{
		$ch = curl_init();
		$fields_string = '';
		foreach($this->postFields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');

		if($this->queryData && count($this->queryData) > 0) {
			foreach($this->queryData as $key=>$value) { 
				$fields_string .= $key.'='.$value.'&'; 
			}
			rtrim($fields_string, '&');			
		}
		curl_setopt($ch, CURLOPT_URL, $this->uri);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch,CURLOPT_HEADER,true);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$res = curl_exec( $ch );
		list($headersRes, $response) = explode("\r\n\r\n", $res, 2);;
		$headers = Extensions::getHeadersFromCurlResponse($headersRes);
		foreach ($headers as $key => $value) {
			$this->response->addHeader((string)$key, (string)$value);
		}	
		curl_close ($ch);
		return new HttpMessage($response, $this);

		$server_output = curl_exec ($ch);
		$info = curl_getinfo($ch);
		$headers = Extensions::getHeadersFromCurlResponse($server_output);
		foreach ($headers as $key => $value) {
			$this->response->addHeader((string)$key, (string)$value);
		}	

		curl_close ($ch);
		$message = new HttpMessage($server_output, $this);
		return $message;
	}

	
}