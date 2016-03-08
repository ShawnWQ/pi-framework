<?hh

namespace Pi\Host\Handlers;
use Pi\Interfaces\IRequest;
use Pi\Interfaces\IResponse;
use Pi\Host\HostProvider;
use Pi\ServiceModel\NotFoundRequest;
use Pi\FileSystem\FileType;
use Pi\FileSystem\FileExtensions;
use Pi\Common\ClassUtils,
	Pi\Extensions,
	Pi\HttpResult;

class RestHandler extends AbstractPiHandler {


	public function createRequest(IRequest $request, string $operationName)
	{

		$requestType = $this->getOperationType($operationName);
		$req = new $requestType();

		// populate DTO
	    $rc = new \ReflectionClass($req);
	    $methods = $rc->getMethods();
	    $body =  json_decode(file_get_contents('php://input'), true);

		$route = $this->appHost->routes()->getByRequest($requestType);


		$httpMethod = $request instanceof IRequest ? $request->httpMethod() : 'GET';

		if($httpMethod === 'POST' && isset($_SERVER['HTTP_ORIGIN']) && $request->serverName() === $request->httpOrigin()) {
			$body = json_decode(file_get_contents('php://input'), true);
		}

		if($body == null) {
			$body = $_POST;
		}
		
		foreach($methods as $method)
	    {
            $n = ClassUtils::getMethodName($method->name);
            if($n === '__construct') {
                continue;
            }

	    	$value = null;
            if(isset($route->params[$n])) {
                $value = $route->params[$n];
            }
            else if(isset($body[$n])) {
                $value = $body[$n];
            } else if($request->parameters()->get($n) !== null) {
            	$value = $request->parameters()->get($n);
            }


	      	$attrs = $method->getAttributes();

            $isInt = false;

            if($method->getAttribute('Int') !== null) {
            	$isInt = true;
            }
            if($method->getAttribute('Request') === null) {
	      	//continue;
	      	}

            if(is_null($value)) {
            	//continue;
            }

            if($method->getAttribute('ObjectId') !== null && !is_null($value)) {
                $reflProp = $rc->getProperty($n);
                $reflProp->setAccessible(true);
                $reflProp->setValue($req, new \MongoId($value));

            } else if($method->getAttribute('Enumerator') !== null) {
            	
            	$enum =  $method->getAttribute('Enumerator')[0];
            	
            	if($enum::isValid((int)$value)) {
            		$reflProp = $rc->getProperty($n);
	                $reflProp->setAccessible(true);
	                $reflProp->setValue($req, (int)$value);
	                
            	}
            }

            else if(array_key_exists('File', $attrs)){

                if($httpMethod === 'POST' && is_array($_FILES) && count($_FILES) === 1) {
	        		$files = FileExtensions::convertRequestFilesToModels($_FILES);
					if(is_array($files)) {
						$req->$n($files[0]);
					}
	     		 }
	      	} else if(array_key_exists('DateTime', $attrs)) {
	      		$reflProp = $rc->getProperty($n);
	      		$reflProp->setAccessible(true);
	      		$reflProp->setValue($req, new \DateTime($value));
	      	}

	      else if($rc->hasProperty($n) && isset($body[$n])) {
				$reflProp = $rc->getProperty($n);
				$reflProp->setAccessible(true);
				$reflProp->setValue($req, $body[$n]);

    			continue;
			} else if($rc->hasProperty($n) && isset($route->params[$n])) {
				$reflProp = $rc->getProperty($n);

				$reflProp->setAccessible(true);

				$value =  $this->getPropType($reflProp) === 'MongoId' || array_key_exists('ObjectId', $attrs) ? new \MongoId($route->params[$n]) : $route->params[$n];

				$reflProp->setValue($req, $value);
			} else if($rc->hasProperty($n) && $request->parameters()->contains($n)) {
				$reflProp = $rc->getProperty($n);
				$reflProp->setAccessible(true);
				$v = $isInt ? intval($request->parameters()->get($n)) : $request->parameters()->get($n);
				$reflProp->setValue($req, $v);
			}

	      else if(isset($body[$n])) {

	        $req->$n($body[$n]);

	      } else if(isset($route->params[$n])){

					if( $method->getAttribute('Id') !== null)  {
						$value = $route->params()[$n];

				// validation is done once the object is filled
				if(!\MongoId::isValid($value)) {
					$req->$n(new \MongoId($value));
				}

			} else {

				$req->$n($route->params()[$n]);
			}

		}
	   
	   }
		return $req;
		// continua a popular a resposta.
	}

	protected function getPropType($property)
	{
		return isset($property->info['type']) ? $property->info['type'] : null;
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
		$httpRes->addHeader('Content-Type', 'text/json');
		
		$host = $this->appHost;
		if($this->appHost->callPreRequestFilters($httpReq, $httpRes)) {
			return null;
		}

		$request = $this->createRequest($httpReq, $operationName);

		if($this->appHost->callPreInitRequestFiltersClasses($httpReq, $httpRes, $request)) {
			return null;
		}
		
		$host->callRequestFiltersClasses($httpReq, $httpRes, $request);
		$host->callRequestFiltersClasses($httpReq, $httpRes, $request);
		$host->callRequestFiltersClasses($httpReq, $httpRes, $request);

		$httpReq->setDto($request);
		$response = $this->getResponse($httpReq, $request);

		$callback = function($response) use($httpReq, $httpRes, $host){
			$host->callResponseFilters(-1, $httpReq, $httpRes);

			if(Extensions::requestHasReturnSession($httpReq)) {
				$dto = (object)$response;
				$dto->session = $httpReq->getSession();
				$response = $dto;
			}
			if($response instanceof HttpResult) {
				foreach ($response->headers() as $key => $value) {
					$httpRes->addHeader($key, $value);
				}
			}
			$httpRes->writeDto($httpReq, $response);
			$httpRes->endRequest(false);

		};
		$errorCallback = function() {

		};

		return $this->handleResponse($response, $callback, $errorCallback);
	}
}
