<?hh

namespace Pi\ServiceInterface;
use Pi\Service;
use Pi\ServiceModel\BatchOperation;
use Pi\ServiceModel\GetBatchOperation;

class BatchService extends Service {
	
	<<Request,Method('POST'),Route('/batch')>>
	public function get(GetBatchOperation $request)
	{

	}
}