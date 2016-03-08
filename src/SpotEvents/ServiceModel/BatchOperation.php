<?hh

namespace Pi\ServiceModel;

class BatchOperation {
	
	public function __construct(
		protected string $method,
		protected string $url,
		protected $dto)
	{

	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getDto()
	{
		return $this->dto;
	}
}