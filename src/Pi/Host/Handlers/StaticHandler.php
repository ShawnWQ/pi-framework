<?hh

namespace Pi\Host\Handlers;

use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Interfaces\IHttpRequest;
use Pi\Host\HostProvider;
use Pi\ServiceModel\NotFoundRequest;
use Pi\FileSystem\FileType;
use Pi\FileSystem\FileExtensions;

class StaticHandler extends AbstractPiHandler {

	public function __construct(protected string $dirPath)
	{

	}

	public function createRequest(IRequest $request, string $operationName)
	{
		$req = new HtmlGet($this->dirPath. $request->requestUri());
		return $req;
		// continua a popular a resposta.
	}

	public function createResponse(IRequest $request, $requestDto)
	{

	}

	public function getResponse(IRequest $httpReq, $request)
	{
		
	}

	public function processRequestAsync(IRequest $httpReq, IResponse $httpRes, string $operationName)
	{
		
	}
	
	public function processRequest(IRequest $httpReq, IResponse $httpRes, string $operationName)
	{

		$request = $this->createRequest($httpReq, $operationName);	
		$httpReq->setDto($request);
		$response = $this->getResponse($httpReq, $request);
		$callback = function($response) use($httpReq, $httpRes, $request){
			$fileName = $request->fileName();
			readfile($fileName);
			exit;
		};

		$errorCallback = function() {

		};

		return $this->handleResponse($response, $callback, $errorCallback);
	}
}