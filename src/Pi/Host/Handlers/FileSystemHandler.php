<?hh

namespace Pi\Host\Handlers;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Host\HostProvider;
use Pi\ServiceModel\NotFoundRequest;
use Pi\FileSystem\FileType;
use Pi\FileSystem\FileExtensions;
use Pi\FileSystem\FileGet;
use Pi\FileSystem\FileMimeType;
use Pi\Validation\InlineValidator;
use Pi\Validation\Validators\IdValidator;
use Pi\Extensions;

/**
 * Handle to deal with Files operations
 */
class FileSystemHandler extends AbstractPiHandler {


	public function createRequest(IRequest $request, string $operationName)
	{
		if(!$request instanceof IRequest) {
			throw new \Exception('File system should be handled with FileSystem handler');
		}

	    $route = $this->appHost->routes()->getByRequest(get_class(new FileGet()));

	    $request = new FileGet();
	    if(\MongoId::isValid($route->params()['fileId'])) {
	    	$request->fileId(new \MongoId($route->params()['fileId']));
	    }

	    /*$validator = new InlineValidator();

	    $validator->ruleFor('fileId')->setValidator(new IdValidator());

	    $result = $validator->validate($request);
	    if(!$result) {
	    	throw new \Exception('Invalid id. Shoulndt, equal to a 404');*/


		return $request;
	}

	public function createResponse(IRequest $request, $requestDto)
	{

	}

	public function getResponse(IRequest $httpReq, $request)
	{
		// set htttpreq.requestattribute
		return self::executeService($request, $httpReq);
	}

	public function processRequestAsync(IRequest $httpReq, IResponse $httpRes, string $operationName)
	{
		return $this->processRequest($httpReq, $httpRes, $operationName);
	}

	public function processRequest(IRequest $httpReq, IResponse $httpRes, string $operationName)
	{
		// bind from form data, ec
		$request = $this->createRequest($httpReq, $operationName);

		$httpReq->setDto($request);
		$response = $this->getResponse($httpReq, $request);


		$callback = function($response) use($httpReq, $httpRes,$response){

	    $fileName = $response->fileName();

		  $content = file_get_contents($fileName);
			switch($response->extension()){
				case FileType::Pdf:
				header('Content-Type: ' . FileMimeType::PDF);
				break;

				case FileType::PNG:
				header('Content-Type: ' . FileMimeType::PNG);
				break;

				case FileType::JPG:
				header('Content-Type: ' . FileMimeType::JPG);
				break;

				case FileType::JPEG:
				header('Content-Type: ' . FileMimeType::JPEG);
				break;

				case FileType::M4v:
				header('Content-Type: ' . FileMimeType::M4v);
				break;

				case FileType::M3U:
				header('Content-Type: ' . FileMimeType::M3U);
				break;

				case FileType::Mdb:
				header('Content-Type: ' . FileMimeType::MicrosoftAccess);
				break;

				case FileType::Asf:
				header('Content-Type: ' . FileMimeType::MicrosoftASF);
				break;

				case FileType::Exe:
				header('Content-Type: ' . FileMimeType::MicrosoftApplication);
				break;

				default:
//					throw new \Exception(sprintf('File extension not supported by FileHandler. Extension is: %s', $response->extension()));
			}

			header('Content-Length: '. (string)filesize( $fileName ));
			header('Content-disposition: inline; filename="' . $fileName . '"');
			header('Cache-Control: public, must-revalidate, max-age=0');
			header('Pragma: public');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

			echo $content;
		};

		$errorCallback = function() {

		};

		return $this->handleResponse($response, $callback, $errorCallback);
	}


}
