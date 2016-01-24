<?hh

namespace Pi\Interfaces;

interface  IServiceMetadata {

	public function getRequestTypes() : Vector;

	public function getServicesTypes() : Vector;

	public function add($serviceType, $requestType, $responseType = null) : void;

	public function getImplementedActions($serviceType, $requestType);

	public function getOperationType(string $operationTypeName);

	public function getOperation($operationType);

	public function getServiceTypeByRequest($requestType);

	public function getResponseTypeByRequest($requestType);

	public function getCache() : array;

}